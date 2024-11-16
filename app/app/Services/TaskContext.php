<?php

namespace App\Services;

use App\Contracts\TaskContract;

class TaskContext
{
    protected $service;

    public function __construct(TaskContract $strategy)
    {
        $this->service = $strategy;
    }

    public function execute(array $payload)
    {
        return $this->service->execute($payload);
    }
}
