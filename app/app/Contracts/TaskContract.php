<?php

namespace App\Contracts;

use App\Services\TaskService;

interface TaskContract
{
    public function execute(array $payload, TaskService $taskService = null);
}
