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

    private Task $task;
    private TaskManager $taskManager;
    private TaskServiceFactory $taskServiceFactory;
    private $uuid;

    /**
     * Create a new job instance.
     */
    public function __construct($taskId)
    {
        $this->task = Task::where('id', $taskId)->firstOrFail();
        $this->taskManager = new TaskManager($this->task);
        $this->uuid = $this->task->uuid;
        $this->taskServiceFactory = app(TaskServiceFactory::class);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $taskType = $this->task->type;
        $adapter = $this->taskServiceFactory->createAdapter($taskType);
        $service = $this->taskServiceFactory->createHandler($taskType);

        $payload = $adapter->prepare($this->task->payload, $this->taskManager);
        $service->execute($payload, $this->taskManager);
    }
}
