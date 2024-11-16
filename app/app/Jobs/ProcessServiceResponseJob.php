<?php

namespace App\Jobs;

use App\Helpers\FileUploadHelper;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessServiceResponseJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;
    private $payload;
    private TaskService $taskService;
    /**
     * Create a new job instance.
     */
    public function __construct($taskId, $payload)
    {
        $this->payload = $payload;
        $task = Task::find($taskId);
        $this->taskService = new TaskService($task);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (!$this->taskService->hasTask()) {
            Log::info('Task not found', $this->payload);
            return;
        }

        if ($this->payload['type'] === 'file') {
            $status = FileUploadHelper::FILE_STATUS_COMPLETED;

            $result = [
                'filename' => $this->payload['filename'],
            ];

            if (!$this->payload['status']) {
                $status = FileUploadHelper::FILE_STATUS_ERROR;
            }

            $this->taskService->updateFileStatus($this->payload['hash'], $status, $result);

            if ($this->payload['index'] === $this->payload['total']) {
                $this->taskService->setComplete();
            }
        }
    }
}
