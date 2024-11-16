<?php

namespace App\Jobs;

use App\Factories\TaskServiceFactory;
use App\Helpers\FileUploadHelper;
use App\Models\Task;
use App\Services\TaskContext;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessTaskJob implements ShouldQueue
{
    use Queueable;

    private Task $task;
    private $uuid;

    /**
     * Create a new job instance.
     */
    public function __construct($taskId)
    {
        $this->task = Task::where('id', $taskId)->firstOrFail();
        $this->uuid = $this->task->uuid;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $service = TaskServiceFactory::make($this->task->type);
        $taskContext = new TaskContext($service);

        $payload = $this->prepareData($this->task->payload);

        $taskContext->execute($payload);
    }

    private function prepareData(array $payload): array
    {
        $payload['task_id'] = $this->task->id;

        if (isset($payload['files'])) {
            foreach ($payload['files'] as &$file) {
                $file['dir'] = FileUploadHelper::getDir($this->uuid);
                $file['path'] = FileUploadHelper::getFilePathForService($this->uuid, $file['hash'], $file['filename']);
                $file['path_original'] = FileUploadHelper::getFilePathOriginal($this->uuid, $file['hash'], $file['filename']);
            }
        }

        return $payload;
    }
}
