<?php

namespace App\Services;

use App\Models\Task;

class TaskService
{
    public ?Task $task = null;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function setTask(Task $task): void
    {
        $this->task = $task;
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

    public function updateFileStatus($hash, $status, $result): void
    {
        $payload = $this->task->payload;

        if (!empty($payload['files'])) {
            foreach ($payload['files'] as &$file) {
                if ($file['hash'] === $hash) {
                    $file['status'] = $status;
                    $file['result'] = $result;

                    $this->task->payload = $payload;
                    $this->task->save();
                    var_dump($this->task);
                    break;
                }
            }
        }
    }
}
