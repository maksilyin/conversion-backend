<?php

namespace App\Jobs;

use App\Factories\TaskServiceFactory;
use App\Helpers\FileUploadHelper;
use App\Models\Task;
use App\Services\TaskContext;
use App\Services\TaskManager;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessTaskJob implements ShouldQueue
{
    use Queueable;

    private $taskUuid;

    /**
     * Create a new job instance.
     */
    public function __construct($taskUuid)
    {
        $this->taskUuid = $taskUuid;
    }

    /**
     * Execute the job.
     * @throws \Exception
     */
    public function handle(): void
    {
        $taskManager = new TaskManager(null, $this->taskUuid);
        $task = $taskManager->getTask();
        $taskServiceFactory = app(TaskServiceFactory::class);

        $taskType = $task->type;
        $adapter = $taskServiceFactory->createAdapter($taskType);
        $service = $taskServiceFactory->createHandler($taskType);

        $payload = $adapter->prepare($taskManager->getPayload(), $taskManager);
        $service->execute($payload, $taskManager);
    }
}
