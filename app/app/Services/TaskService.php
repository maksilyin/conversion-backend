<?php

namespace App\Services;

use App\Helpers\FileUploadHelper;
use App\Models\Task;
use Illuminate\Support\Facades\DB;

class TaskService
{
    public ?Task $task = null;

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
    }

    public function hasTask(): bool
    {
        return $this->task !== null;
    }

    public function setComplete(): void
    {
        $this->task->status = 'complete';
        $this->task->save();
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
                            $file['result'] = $result;
                        }

                        $this->task->payload = $payload;
                        $this->task->save();
                        break;
                    }
                }
            }
        });
    }
}
