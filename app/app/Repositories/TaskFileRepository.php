<?php

namespace App\Repositories;

use App\Helpers\FileUploadHelper;
use App\Models\Task;
use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;

class TaskFileRepository
{
    private Task $task;
    private array $files;

    public function __construct(Task $task)
    {
        $this->task = $task;
        $this->files = $task->payload['files'] ?? [];

        foreach ($this->files as &$file) {
            if (isset($file['result'])) {
                $file['result'] = current($file['result']);
            }
        }
    }

    public function getFileByHash(string $hash, $withPath = false)
    {
        if (empty($this->files)) {
            throw new FileNotFoundException("No files available in payload.");
        }

        $fileItem = current(array_filter($this->files, fn($file) => $file['hash'] === $hash));

        if (!$fileItem) {
            throw new FileNotFoundException("File with hash {$hash} not found.");
        }

        if ($withPath) {
            $filename = FileUploadHelper::getFileName($fileItem['hash'], $fileItem['filename']);
            $fileItem['fullPath'] = $this->getFullPath($filename);
        }

        return $fileItem;
    }

    public function isFileCompleted(array $file): bool
    {
        return $file['status'] === FileUploadHelper::FILE_STATUS_COMPLETED;
    }

    public function getFullPath(string $filename): string
    {
        $fullPath = FileUploadHelper::getFile($this->task->uuid, $filename);

        if (!$fullPath) {
            throw new Exception("Failed to retrieve the full path for the file.");
        }

        return $fullPath;
    }

    public function getFileResult(string $hash)
    {
        $file = $this->getFileByHash($hash);

        if (!$this->isFileCompleted($file)) {
            throw new Exception("File is not complete.");
        }

        $file['result']['fullPath'] = $this->getFullPath($file['result']['filename']);

        return $file['result'];
    }

    public function getFileResultList(): array
    {
        $arFiles = [];

        if (empty($this->files)) {
            return $arFiles;
        }

        foreach ($this->files as $file) {
            try {
                if (!$this->isFileCompleted($file)) {
                    continue;
                }

                $file['result']['fullPath'] = $this->getFullPath($file['result']['filename']);
                $arFiles[] = $file['result'];
            }
            catch (Exception $e) {
                continue;
            }
        }

        return $arFiles;
    }

    public function getPathForService($hash): string
    {
        $file = $this->getFileByHash($hash);
        return FileUploadHelper::getFilePathForService($this->task->uuid, $hash, $file->filename);
    }
}
