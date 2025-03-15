<?php

namespace App\Services;

use App\Models\Task;

class TaskCleaner
{
    private Task $task;
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function clear()
    {
        $this->task->delete();
        $this->task->status = Task::STATUS_CLEAR;
        $this->task->save();
    }
}
