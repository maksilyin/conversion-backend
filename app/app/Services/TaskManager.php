<?php

namespace App\Services;

use App\Helpers\FileUploadHelper;
use App\Models\Task;
use App\Repositories\TaskFileRepository;
use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\DB;
use phpseclib3\File\ASN1\Maps\Extension;

class TaskManager
{
    const STATUS_COMPLETE = 'complete';
    const STATUS_PENDING = 'pending';
    private ?Task $task = null;
    private $payload = [];
    private TaskFileRepository $taskFileRepository;

    public function __construct(Task $task = null, $taskUuid = false)
    {
        $this->setTask($task, $taskUuid);
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
        $this->taskFileRepository = new TaskFileRepository($task);
    }

    public function hasTask(): bool
    {
        return $this->task !== null;
    }

    public function setComplete(): void
    {
        $this->task->status = self::STATUS_COMPLETE;
        $this->task->save();
    }

    public function getId()
    {
        return $this->task->id;
    }

    public function getUuid()
    {
        return $this->task->uuid;
    }

    public function incrementJob()
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
            $this->task->save();
        });
    }

    public function getCompletedJobs()
    {
        return $this->task->payload['completed_jobs'];
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
                        $this->task->save();
                        break;
                    }
                }
            }
        });
    }

    public function isTaskCompleted(): bool
    {
        return $this->task->status === self::STATUS_COMPLETE;
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
}
