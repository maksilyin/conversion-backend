<?php

namespace App\Services;

use App\Helpers\FileUploadHelper;
use App\Models\Task;
use Illuminate\Support\Facades\Storage;

class TaskCleaner
{
    private Task $task;
    private FileService $fileService;
    public function __construct(Task $task)
    {
        $this->task = $task;
        $this->fileService = new FileService($task->uuid);
    }

    public function clear()
    {
        $payload = $this->task->payload;
        $this->fileService->deleteTaskFolder();
        $payload['files'] = [];
        $this->task->payload = $payload;
        $this->task->status = Task::STATUS_CLEAR;
        $this->task->save();
    }
}
