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
            $data = [
                'task_id' => $payload['task_id'],
                'hash' => $file['hash'],
                'file_path' => $file['service_path'],
                'output_format' => current($file['params']['convert']),
                'index' => $index,
                'total' => count($payload['files']),
            ];

            $taskManager->setFileStatusProcessing($file['hash']);

            Queue::pushRaw(json_encode($data), 'convert');
        }
        if ($allFilesSkipped && $taskManager) {
            $taskManager->setComplete();
        }
    }
}
