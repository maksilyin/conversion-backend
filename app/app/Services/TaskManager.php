<?php

namespace App\Services;

use App\Helpers\FileUploadHelper;
use App\Models\Task;
use App\Repositories\TaskFileRepository;
use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use phpseclib3\File\ASN1\Maps\Extension;

class TaskManager
{
    private ?Task $task = null;
    private array $payload = [];
    private TaskFileRepository $taskFileRepository;
    private TaskCleaner $taskCleaner;
    private Bool $autoSave;

    /**
     * @throws Exception
     */
    public function __construct(Task $task = null, $taskUuid = false, $autoSave = true)
    {
        if ($task || $taskUuid) {
            $this->setTask($task, $taskUuid);
        }
        $this->autoSave = $autoSave;
    }

    public function setTask(Task $task = null, $taskUuid = false): void
    {
        if ($task) {
            $this->task = $task;
        }
        else if ($taskUuid) {
            $this->task = Task::getByUuid($taskUuid);
        }

        if (!$this->task) {
            throw new Exception("Task not found");
        }
        $this->payload = $this->task->payload;
        $this->taskFileRepository = new TaskFileRepository($this->task);
        $this->taskCleaner = new TaskCleaner($this->task);
    }

    public function hasTask(): bool
    {
        return $this->task !== null;
    }

    public function setComplete(): void
    {
        $this->setStatus(Task::STATUS_COMPLETE);
    }

    public function getId()
    {
        return $this->task->id;
    }

    public function getUuid()
    {
        return $this->task->uuid;
    }

    public function getTask(): Task
    {
        return $this->task;
    }

    public function getPayload(): array
    {
        return $this->task->payload;
    }

    public function getPayloadKey($key)
    {
        return $this->task->payload[$key];
    }

    public function isCanLoadFile(): bool
    {
        return $this->status() === Task::STATUS_CREATED || $this->status() === Task::STATUS_COMPLETE;
    }

    public function save(): void
    {
        $this->task->save();
    }

    public function autoSave(): void
    {
        if ($this->autoSave) {
            $this->save();
        }
    }

    public function status()
    {
        return $this->task->status;
    }

    public function setStatus($status): void
    {
        $this->task->status = $status;
        $this->autoSave();
    }

    public function incrementJob(): void
    {
        DB::transaction(function () {
            $this->task->refresh();
            $payload = $this->task->payload;

            if (isset($payload['completed_jobs'])) {
                $payload['completed_jobs']++;
            }
            else {
                $payload['completed_jobs'] = 1;
            }

            $this->task->payload = $payload;
            $this->autoSave();
        });
    }

    public function getCompletedJobs()
    {
        return $this->getPayloadKey('completed_jobs');
    }

    public function setFileStatusProcessing($hash): void
    {
        $this->updateFileStatus($hash, FileUploadHelper::FILE_STATUS_PROCESSING);
    }

    public function setPayloadData(array $data): void
    {
        if (isset($data['files'])) {
            $this->taskFileRepository->setFiles($data['files']);
            unset($data['files']);
        }
        $payload = $this->getPayload();

        foreach ($data as $key => $dataItem) {
            $payload[$key] = $dataItem;
        }

        $this->task->payload = $payload;
        $this->autoSave();
    }

    public function addFile($fileData): void
    {
        DB::transaction(function () use($fileData) {
            $this->task->refresh();
            $payload = $this->task->payload;

            if (empty($payload['files'])) {
                $payload['files'] = [];
            }
            $payload['files'][] = $fileData;

            $this->task->payload = $payload;
            $this->autoSave();
        });
    }

    public function deleteFileByHash($hash): void
    {
        $this->taskFileRepository->deleteFileByHash($hash);
    }

    public function isTaskCompleted(): bool
    {
        return $this->task->status === Task::STATUS_COMPLETE;
    }

    public function getFiles(): array
    {
        return $this->taskFileRepository->getFiles();
    }

    public function getUploadedFiles(): array
    {
        return $this->taskFileRepository->getUploadedFiles();
    }

    public function getFileById(string $hash, bool $withPath = false): ?array
    {
        return $this->taskFileRepository->getFileById($hash, $withPath);
    }

    public function getFileResult(string $hash)
    {
        return $this->taskFileRepository->getFileResult($hash);
    }

    public function getFileResultList(): array
    {
        return $this->taskFileRepository->getFileResultList();
    }

    public function getPathForService(string $hash): string
    {
        return $this->taskFileRepository->getPathForService($hash);
    }

    public function updateFileStatus($hash, $status, $result = null): void
    {
        $this->taskFileRepository->updateFileStatus($hash, $status, $result);
    }

    public function clearTask(): void
    {
        $this->taskCleaner->clear();
    }
}
