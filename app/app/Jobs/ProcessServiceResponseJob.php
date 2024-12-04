<?php

namespace App\Jobs;

use App\Events\TaskUpdated;
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
    private $task;
    private TaskService $taskService;
    /**
     * Create a new job instance.
     */
    public function __construct($taskId, $payload)
    {
        $this->payload = $payload;
        $this->task = Task::find($taskId);
        $this->taskService = new TaskService($this->task);
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

            $serviceResult = $this->payload['result'];

            if ($serviceResult['status']) {
                $status = FileUploadHelper::FILE_STATUS_COMPLETED;

                if ($fileArray = FileUploadHelper::getFileArray($this->task->uuid, $serviceResult['filename'])) {
                    unset($fileArray['src']);
                }
                $result = $fileArray;
            }
            else {
                $status = FileUploadHelper::FILE_STATUS_ERROR;
                $result = $serviceResult;
            }

            $this->taskService->updateFileStatus($this->payload['hash'], $status, $result);

            if ($this->payload['index'] === $this->payload['total'] - 1) {
                $this->taskService->setComplete();
            }
        }
    }
}
