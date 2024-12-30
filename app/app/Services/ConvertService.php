<?php

namespace App\Services;

use App\Contracts\TaskContract;
use Illuminate\Support\Facades\Queue;

class ConvertService implements TaskContract
{
    public function execute(array $payload, TaskManager $taskManager): void
    {
        if (!isset($payload['files']) || !isset($payload['task_id'])) {
            return;
        }

        $allFilesSkipped = true;

        foreach ($payload['files'] as $index => $file) {
            $allFilesSkipped = false;
            $taskManager->setFileStatusProcessing($file['hash']);

            foreach ($file['params']['convert'] as $formatIndex => $outputFormat) {
                echo "formatIndex: ".$formatIndex.', outputFormat:'.$outputFormat.' ; <br>';
                $data = [
                    'task_id' => $payload['task_id'],
                    'hash' => $file['hash'],
                    'file_path' => $file['service_path'],
                    'file_type' => $file['file_type'],
                    'output_format' => $outputFormat,
                    'index' => $index,
                    'total' => count($payload['files']),
                    'formatIndex' => $formatIndex
                ];

                Queue::pushRaw(json_encode($data), 'convert');
            }
        }
        if ($allFilesSkipped && $taskManager) {
            $taskManager->setComplete();
        }
    }
}
