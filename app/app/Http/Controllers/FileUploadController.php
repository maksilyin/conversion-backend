<?php

namespace App\Http\Controllers;

use App\Helpers\FileUploadHelper;
use App\Models\Task;
use Exception;
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

    public function download(Request $request)
    {
        $request->validate([
            'task' => 'required|uuid',
            'filename' => 'required|string',
        ]);

        $task = $request->input('task');
        $filename = $request->input('filename');

        $fileArray = FileUploadHelper::getFileArray($task, $filename);

        if (!$fileArray) {
            return response()->json(['error' => 'File not found'], 404);
        }

        $fileContent = file_get_contents($fileArray['src']);
        $mimeType = $fileArray['mimetype'];
        $fileName = $fileArray['originalName'];

        return response($fileContent, 200, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
            'Content-Length' => strlen($fileContent),
        ]);
    }

    public function showImg($task, $filename)
    {
        if (!$fileArray = FileUploadHelper::getFileArray($task, $filename)) {
            abort(404);
        }

        $fileContent = file_get_contents($fileArray['src']);
        $mimeType = $fileArray['mimetype'];

        if (!strpos($mimeType, 'image/') === false) {
            abort(403);
        }

        return response($fileContent, 200, [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline',
            'Content-Length' => strlen($fileContent),
        ]);
    }

    /**
     * @throws Exception
     */
    public function downloadZip(Request $request)
    {
        $request->validate([
            'task' => 'required|uuid',
        ]);

        $task = $request->input('task');
        $oTask = Task::getByUuid($task);

        if (!$oTask || $oTask->status !== 'complete') {
            abort(400);
        }

        $payload = $oTask->payload;
        $arFiles = [];

        foreach ($payload['files'] as $file) {
            if ($file['status'] === FileUploadHelper::FILE_STATUS_COMPLETED) {
                if ($filePath = FileUploadHelper::getFile($task, $file['result']['filename'])) {
                    $arFiles[] = $filePath;
                }
            }
        }

        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        $zipPath = storage_path('app/temp/'.$task.'.zip');
        $zip = new \ZipArchive();

        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
            foreach ($arFiles as $file) {
                if (file_exists($file)) {
                    $zip->addFile($file, FileUploadHelper::getOriginalName(basename($file)));
                }
            }

            $zip->close();
        }
        else {
            throw new Exception("Не удалось создать архив");
        }

        return Response::download($zipPath)->deleteFileAfterSend(true);
    }
}
