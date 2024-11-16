<?php

namespace App\Http\Controllers;

use App\Helpers\FileUploadHelper;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
        $result = [];

        if ($chunkIndex === $total) {
            $outputFile = $this->completeUpload($fileIdentifier, $task, $request->input('filename'));
            $result = FileUploadHelper::getFileMimeType($outputFile);
        }

        return response()->json([
            'status' => $status,
            'hash' => $fileIdentifier,
            'result' => $result,
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
        //rmdir($tempDir);

        return $finalPath . $originalFileName;
    }
}
