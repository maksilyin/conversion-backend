<?php

namespace App\Contracts;

use App\Services\TaskManager;

interface TaskContract
{
    public function execute(array $payload, TaskManager $taskManager);
}
