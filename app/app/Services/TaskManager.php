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
        $this->setTask($task, $taskUuid);
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

    public function updateFileStatus($hash, $status, $result = false): void
    {
        DB::transaction(function () use ($hash, $status, $result) {
            $this->task->refresh();
            $payload = $this->task->payload;

            if (!empty($payload['files'])) {
                foreach ($payload['files'] as &$file) {
                    if ($file['hash'] === $hash) {
                        $file['status'] = $status;

                        if ($result) {
                            if (empty($file['result'])) {
                                $file['result'] = [];
                            }
                            $file['result'][] = $result;
                        }

                        $this->task->payload = $payload;
                        $this->autoSave();
                        break;
                    }
                }
            }
        });
    }

    public function setPayloadData(array $data): void
    {
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
        $payload = $this->getPayload();

        if (empty($payload['files'])) {
            return;
        }

        $payload['files'] = array_filter($payload['files'], function ($file) use ($hash) {
            return $file['hash'] !== $hash;
        });

        $this->task->payload = $payload;
        $this->autoSave();
    }

    public function isTaskCompleted(): bool
    {
        return $this->task->status === Task::STATUS_COMPLETE;
    }

    public function getFileByHash(string $hash, bool $withPath = false): ?array
    {
        return $this->taskFileRepository->getFileByHash($hash, $withPath);
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

    public function clearTask(): void
    {
        $this->taskCleaner->clear();
    }
}
