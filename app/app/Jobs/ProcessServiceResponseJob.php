<?php

namespace App\Jobs;

use App\Helpers\FileUploadHelper;
use App\Models\Task;
use App\Services\TaskManager;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PHPUnit\Exception;

class ProcessServiceResponseJob implements ShouldQueue
{
    use Queueable, InteractsWithQueue, SerializesModels;
    private $payload;
    private $task;
    private TaskManager $taskManager;
    /**
     * Create a new job instance.
     */
    public function __construct($taskId, $payload)
    {
        $this->payload = $payload;
        $this->task = Task::getByUuid($taskId);
        $this->taskManager = new TaskManager($this->task);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->payload['type'] === 'file') {
            $serviceResult = $this->payload['result'];
            try {
                if ($serviceResult['status']) {
                    $status = FileUploadHelper::FILE_STATUS_COMPLETED;
                    $filePath = FileUploadHelper::getFileFromService($this->task->uuid, $serviceResult['output']);
                    if ($filePath && $fileArray = FileUploadHelper::getFileArray($this->task->uuid, $filePath)) {
                        unset($fileArray['src']);
                    }
                    $result = [
                        ...$fileArray,
                        'status' => $serviceResult['status'],
                    ];
                }
                else {
                    $status = FileUploadHelper::FILE_STATUS_ERROR;
                    $result = $serviceResult;
                }
            }
            catch (Exception $e) {
                $status = FileUploadHelper::FILE_STATUS_ERROR;
                $result = [
                    "status" => false,
                    "error" => $e->getMessage(),
                ];
            }
            $this->taskManager->updateFileStatus($this->payload['hash'], $status, $result);
            $this->taskManager->incrementJob();

            if ($this->task->payload['jobs'] == $this->taskManager->getCompletedJobs()) {
                $this->taskManager->setComplete();
            }
        }
    }
}
