<?php

namespace App\Services;

use App\Contracts\TaskContract;
use Illuminate\Support\Facades\Queue;

class ConvertService implements TaskContract
{
    public function execute(array $payload): void
    {
        if (!isset($payload['files']) || !isset($payload['task_id'])) {
            return;
        }

        foreach ($payload['files'] as $index => $file) {
            if (!isset($file['result']) && file_exists($file['path_original']) && !empty($file['convert'])) {
                $data = [
                    'task_id' => $payload['task_id'],
                    'hash' => $file['hash'],
                    'file_path' => $file['path'],
                    'target_format' => current($file['convert']),
                    'index' => $index,
                    'total' => count($payload['files']) - 1,
                ];
                Queue::pushRaw(json_encode($data), 'convert');
            }
        }
    }
}
