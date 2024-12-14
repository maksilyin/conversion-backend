<?php

namespace App\Registry;

use Exception;

class TaskRegistry
{
    private array $tasks = [];

    /**
     * Зарегистрировать задачу.
     */
    public function registerTask(string $taskType, array $config): void
    {
        $this->tasks[$taskType] = $config;
    }

    /**
     * Получить конфигурацию задачи.
     */
    public function getConfig(string $taskType): array
    {
        if (!isset($this->tasks[$taskType])) {
            throw new Exception("Task type '{$taskType}' is not registered.");
        }

        return $this->tasks[$taskType];
    }
}
