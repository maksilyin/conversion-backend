<?php

namespace App\Http\Controllers;

use App\Helpers\FileUploadHelper;
use App\Models\Task;
use App\Services\FileService;
use App\Services\TaskManager;
use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FileUploadController extends Controller
{
    private FileService $fileService;
    public function __construct()
    {
        $this->fileService = new FileService();
    }
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
        $filename = $request->input('filename');

        $this->fileService->setUuid($task);
        $this->fileService->uploadChunk($chunk, $fileIdentifier, $chunkIndex);

        $status = true;
        $fileInfo = [];

        if ($chunkIndex === $total) {
            $outputFile = $this->fileService->mergeChunks($fileIdentifier, $filename);
            $fileInfo = $this->fileService->getFileInfo($outputFile);
        }

        return response()->json([
            'status' => $status,
            'hash' => $fileIdentifier,
            ...$fileInfo
        ]);
    }

    public function deleteFile(Task $task, string $hash): true
    {
        $taskManager = new TaskManager($task);
        $taskManager->updateFileStatus($hash, FileUploadHelper::FILE_STATUS_DELETE);
        $taskManager->deleteFileByHash($hash);

        $this->fileService->setUuid($task->uuid);
        $this->fileService->deleteFileByHash($hash);

        return true;
    }

    public function download(Task $task, string $hash)
    {
        $taskManager = new TaskManager($task);
        $this->fileService->setUuid($task->uuid);
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
