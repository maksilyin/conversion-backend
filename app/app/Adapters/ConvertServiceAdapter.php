<?php

namespace App\Adapters;

use App\Contracts\ServiceAdapterContract;
use App\Helpers\FileUploadHelper;
use App\Services\TaskManager;

class ConvertServiceAdapter implements ServiceAdapterContract
{

    public function filter(string $uuid, array $payload): array
    {
        $data = [
            'files' => []
        ];

        foreach ($payload['files'] as $file) {

            if ($file['status'] != FileUploadHelper::FILE_STATUS_UPLOADED) {
                continue;
            }

            $fileName = FileUploadHelper::getFileName($file['hash'], $file['filename']);
            $fileArray = FileUploadHelper::getFileArray($uuid, $fileName);

            $params = [
                'hash' => $file['hash'],
                'status' => $file['status'],
                'filename' => $file['filename'],
                'extension' => $fileArray['extension'],
                'mimetype' => $fileArray['mimetype'],
                'size' => $fileArray['size'],
                'params' => [
                    'convert' => $file['params']['convert']
                ],
            ];
            $data['files'][] = $params;
        }

        return $data;
    }

    public function prepare(array $payload, TaskManager $taskManager): array
    {
        $uuid = $taskManager->getUuid();
        $payload['task_id'] = $uuid;

        foreach ($payload['files'] as &$file) {
            $file['service_path'] = FileUploadHelper::getFilePathForService($uuid, $file['hash'], $file['filename']);
        }

        return $payload;
    }
}