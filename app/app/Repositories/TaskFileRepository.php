<?php

namespace App\Repositories;

use App\Helpers\FileUploadHelper;
use App\Models\Task;
use App\Services\FileService;
use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Laravel\Reverb\Loggers\Log;

class TaskFileRepository
{
    private Task $task;
    private array $files = [];
    private FileService $fileService;
    private FileRepository $fileRepository;

    public function __construct(Task $task)
    {
        $this->task = $task;
        $this->files = $this->getFiles();
        $this->fileRepository = new FileRepository();
        $this->fileService = new FileService($this->task->uuid);
        $files = $this->getFiles();

        foreach ($files as &$file) {
            if (isset($file['result'])) {
                $file['result'] = current($file['result']);
            }
        }
    }

    public function getFiles(): array
    {
        $files = $this->task->files()->get()->toArray();
        return !empty($files) ? array_column($files, null, 'hash') : [];
    }

    public function getUploadedFiles(): array
    {
        $files = $this->task
            ->files()
            ->where('status', '>=', FileUploadHelper::FILE_STATUS_UPLOADED)
            ->get()
            ->toArray();
        return !empty($files) ? array_column($files, null, 'hash') : [];
    }

    public function getFileById(string $id, $withPath = false)
    {
        if (!isset($this->files[$id])) {
            throw new FileNotFoundException("File with id {$id} not found.");
        }

        $fileItem = $this->files[$id];

        if ($withPath) {
            $fileItem['fullPath'] = $this->fileService->getFileByHash($id, true);
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

    /**
     * Получает результат файла по его ID.
     *
     * @param string $id ID файла.
     * @throws Exception Если файл не завершён.
     * @return array Ассоциативный массив с данными результата файла.
     */
    public function getFileResult(string $id)
    {
        $file = $this->getFileById($id);

        if (!$this->isFileCompleted($file)) {
            throw new Exception("File is not complete.");
        }

        $fileResult = current($file['result']);
        $fileResult['fullPath'] = $this->fileService->getFileResultByHash($id, true);

        return $fileResult;
    }

    public function getFileResultList(): array
    {
        $arFiles = [];

        if (empty($this->files)) {
            return [];
        }

        foreach ($this->files as $file) {
            try {
                if (!$this->isFileCompleted($file)) {
                    continue;
                }
                foreach ($file['result'] as $arFileResult) {
                    $arFileResult['path'] = $this->fileService->getFileResultByHash($file['hash']);
                    $arFileResult['fullPath'] = $this->fileService->getFileResultByHash($file['hash'], true);
                    $arFiles[] = $arFileResult;
                }
            }
            catch (Exception $e) {
                continue;
            }
        }

        return $arFiles;
    }

    public function getPathForService($hash): string
    {
        return $this->fileService->getFilePathForService($hash);
    }

    public function setFiles(array $dataFiles): void
    {
        foreach ($dataFiles as $fileData) {
            if (!isset($this->files[$fileData['hash']])) {
                continue;
            }

            $this->fileRepository->updateFile($fileData['hash'], $fileData);
        }
    }

    public function updateFileStatus($hash, $status, $result = null): void
    {
        $data = [
            'status' => $status,
        ];

        if ($result) {
            $data['result'] = $result;
        }

        $this->fileRepository->updateFile($hash, $data);
    }

    public function deleteFileByHash($hash): void
    {
        $this->fileRepository->deleteFile($hash);
    }
}
