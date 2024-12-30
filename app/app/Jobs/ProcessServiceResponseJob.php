<?php

namespace App\Jobs;

use App\Helpers\FileUploadHelper;
use App\Models\Task;
use App\Services\TaskManager;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

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
            var_dump('ProcessServiceResponseJob '.$serviceResult['extension']);
            if ($serviceResult['status']) {
                var_dump('status '.$serviceResult['extension']);
                $status = FileUploadHelper::FILE_STATUS_COMPLETED;
                var_dump('getFileFromService '.$serviceResult['extension']);
                var_dump('getFileFromService '.$serviceResult['output']);
                $filePath = FileUploadHelper::getFileFromService($this->task->uuid, $serviceResult['output']);
                var_dump('$filePath '.$filePath.' '.$serviceResult['extension']);

                var_dump('getFileArray '.$serviceResult['extension']);
                if ($filePath && $fileArray = FileUploadHelper::getFileArray($this->task->uuid, $filePath)) {
                    unset($fileArray['src']);
                }
                $result = [
                    ...$fileArray,
                    'status' => $serviceResult['status'],
                ];
            }
            else {
                var_dump('no status '.$serviceResult['extension']);
                $status = FileUploadHelper::FILE_STATUS_ERROR;
                $result = $serviceResult;
            }
            var_dump('start updateFileStatus '.$serviceResult['extension']);
            $this->taskManager->updateFileStatus($this->payload['hash'], $status, $result);
            var_dump('stop updateFileStatus '.$serviceResult['extension']);

            if ($this->payload['index'] === $this->payload['total'] - 1) {
                //$this->taskManager->setComplete();
            }
        }
    }
}
