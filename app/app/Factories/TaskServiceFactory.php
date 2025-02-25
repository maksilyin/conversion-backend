<?php

namespace App\Factories;

use App\Contracts\ServiceAdapterContract;
use App\Contracts\TaskContract;
use App\Models\Task;
use App\Registry\TaskRegistry;
use App\Services\ConvertService;
use App\Services\TaskManager;
use Exception;
use Illuminate\Contracts\Validation\ValidationRule;
use InvalidArgumentException;

class TaskServiceFactory
{
    private TaskRegistry $registry;
    protected static array $taskServices = [
        'convert' => ConvertService::class,
    ];

    public function __construct(TaskRegistry $registry)
    {
        $this->registry = $registry;
    }

    public static function make($type): TaskContract
    {
        $service = static::$taskServices[$type] ?? null;

        if (!$service) {
            throw new InvalidArgumentException("Unknown task type: {$type}");
        }

        return app($service);
    }

    /**
     * @throws Exception
     */
    public function hasTaskService(string $taskType): bool
    {
        return !empty($this->registry->getConfig($taskType));
    }

    /**
     * @throws Exception
     */
    public function createValidator(string $taskType, TaskManager $taskManager): ValidationRule
    {
        $config = $this->registry->getConfig($taskType);

        if (!isset($config['validator'])) {
            throw new Exception("No validator found for task type '{$taskType}'.");
        }

        return app($config['validator'], ['taskManager' => $taskManager]);
    }

    /**
     * @throws Exception
     */
    public function createHandler(string $taskType): TaskContract
    {
        $config = $this->registry->getConfig($taskType);

        if (!isset($config['handler'])) {
            throw new Exception("No handler found for task type '{$taskType}'.");
        }

        return app($config['handler']);
    }

    /**
     * @throws Exception
     */
    public function createAdapter(string $taskType): ServiceAdapterContract
    {
        $config = $this->registry->getConfig($taskType);

        return app($config['adapter']);
    }
}
