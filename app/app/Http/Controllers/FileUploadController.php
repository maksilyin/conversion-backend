<?php

namespace App\Http\Controllers;

use App\Helpers\FileUploadHelper;
use App\Models\Task;
use App\Services\TaskManager;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FileUploadController extends Controller
{
    public function uploadChunk(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|file',
            'index' => 'required|integer',
            'total' => 'required|integer',
            'filename' => 'required|string',
            'task' => 'required|uuid',
            'hash' => 'required|uuid',
        ]);

        $chunk = $request->file('file');
        $chunkIndex = $request->input('index');
        $fileIdentifier = $request->input('hash');
        $total = $request->input('total');
        $task = $request->input('task');
        $tempDir = FileUploadHelper::getTmpDir($task);

        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $chunkFileName = FileUploadHelper::getFileName($fileIdentifier, $chunkIndex);

        $chunk->move($tempDir, $chunkFileName);

        $status = true;
        $fileInfo = [];

        if ($chunkIndex === $total) {
            $outputFile = $this->completeUpload($fileIdentifier, $task, $request->input('filename'));
            $fileInfo = FileUploadHelper::getFileInfo($outputFile);

            Cache::forget("uploaded_size_{$fileIdentifier}");
        }

        return response()->json([
            'status' => $status,
            'hash' => $fileIdentifier,
            ...$fileInfo
        ]);
    }

    public function deleteFile(Request $request): true
    {
        $request->validate([
            'filename' => 'required|string',
            'task' => 'required|uuid',
            'hash' => 'required|uuid',
        ]);

        $task = $request->input('task');
        $hash = $request->input('hash');
        $filename = $request->input('filename');
        $path = FileUploadHelper::getFilePathOriginal($task, $hash, $filename);

        if (file_exists($path)) {
            unlink($path);
        }

        return true;
    }

    public function completeUpload($hash, $task, $fileName): string
    {
        $fileIdentifier = $hash;
        $originalFileName = FileUploadHelper::getFileName($fileIdentifier, $fileName);
        $tempDir = FileUploadHelper::getTmpDir($task);;
        $finalPath = FileUploadHelper::getDir($task);

        if (!file_exists($finalPath)) {
            mkdir($finalPath, 0755, true);
        }
        $outputFile = fopen($finalPath . $originalFileName, 'ab');

        $chunkIndex = 1;

        while (file_exists( $tempDir . FileUploadHelper::getFileName($fileIdentifier, $chunkIndex))) {
            $chunkPath = $tempDir . FileUploadHelper::getFileName($fileIdentifier, $chunkIndex);
            $chunk = fopen($chunkPath, 'rb');
            stream_copy_to_stream($chunk, $outputFile);
            fclose($chunk);
            unlink($chunkPath);
            $chunkIndex++;
        }

        fclose($outputFile);

        return $finalPath . $originalFileName;
    }

    public function download(Task $task, string $hash)
    {
        $taskManager = new TaskManager($task);

        $file = $taskManager->getFileResult($hash);

        $fileContent = file_get_contents($file['fullPath']);
        $mimeType = $file['mimetype'];
        $fileName = $file['originalName'];

        return response($fileContent, 200, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
            'Content-Length' => strlen($fileContent),
        ]);
    }

    public function showImg(Task $task, string $hash)
    {
        $taskManager = new TaskManager($task);
        $file = $taskManager->getFileByHash($hash, true);
        $mimeType = $file['mimetype'];

        if (!strpos($mimeType, 'image/') === false) {
            abort(400);
        }

        $fileContent = file_get_contents($file['fullPath']);

        return response($fileContent, 200, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline',
            'Content-Length' => strlen($fileContent),
        ]);
    }
    public function downloadZip(Task $task)
    {
        $taskManager = new TaskManager($task);

        $files = $taskManager->getFileResultList();

        if (empty($files)) {
            abort(400, 'No files available for download.');
        }

        $zipPath = $this->createZip($task->uuid, $files);

        return Response::download($zipPath)->deleteFileAfterSend(true);
    }

    protected function createZip(string $uuid, array $files): string
    {
        $tempDir = storage_path('app/temp');
        $zipPath = $tempDir . '/' . $uuid . '.zip';

        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $zip = new \ZipArchive();

        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            throw new Exception("Failed to create ZIP archive.");
        }

        foreach ($files as $file) {
            $zip->addFile($file['fullPath'], $file['originalName']);
        }

        $zip->close();

        return $zipPath;
    }
}
