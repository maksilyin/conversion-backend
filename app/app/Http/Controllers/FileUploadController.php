<?php

namespace App\Http\Controllers;

use App\Helpers\FileUploadHelper;
use App\Models\File;
use App\Models\Task;
use App\Repositories\FileRepository;
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
    public function create(TaskManager $taskManager, Request $request)
    {
        $request->validate([
            'task' => 'required|uuid',
            'filename' => 'required|string',
        ]);

        if (!$taskManager->isCanLoadFile()) {
            abort(422, 'Files cannot be uploaded while the task is in its current state.');
        }

        $filename = $request->input('filename');
        $size = $request->input('size');

        $file = File::create([
            'task_id' => $taskManager->getId(),
            'filename' => $filename,
            'size' => $size,
            'extension' => pathinfo($filename, PATHINFO_EXTENSION),
        ]);

        return $file->id;
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

            $fileRepository = new FileRepository();
            $fileRepository->updateFile($fileIdentifier, [
                'status' => FileUploadHelper::FILE_STATUS_UPLOADED,
                'mimetype' => $fileInfo['mimetype'],
                'size' => $fileInfo['size'],
                'extension' => $fileInfo['extension'],
            ]);
        }

        return response()->json([
            'status' => $status,
            'hash' => $fileIdentifier,
            ...$fileInfo
        ]);
    }

    public function deleteFile(FileRepository $fileRepository, string $task, string $hash): true
    {
        $fileRepository->deleteFile($hash);
        return true;
    }

    public function download(TaskManager $taskManager, string $task, string $hash)
    {
        $this->fileService->setUuid($task);
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

    public function showImg(TaskManager $taskManager, string $task, string $hash)
    {
        $file = $taskManager->getFileById($hash, true);
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
    public function downloadZip(TaskManager $taskManager, string $task)
    {
        $files = $taskManager->getFileResultList();

        if (empty($files)) {
            abort(400, 'No files available for download.');
        }

        $zipPath = $this->createZip($task, $files);

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
