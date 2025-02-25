<?php

namespace App\Adapters;

use App\Contracts\ServiceAdapterContract;
use App\Helpers\FileUploadHelper;
use App\Models\FileFormat;
use App\Models\Task;
use App\Services\FileService;
use App\Services\TaskManager;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ConvertServiceAdapter implements ServiceAdapterContract
{
    public function create(array $payload): array
    {
        $hash = !empty($payload['hash']) ? $payload['hash'] : (string) Str::uuid();

        return [
            'hash' => $hash,
            'status' => FileUploadHelper::FILE_STATUS_CREATED,
            'filename' => $payload['filename'],
            'extension' => pathinfo($payload['filename'], PATHINFO_EXTENSION),
            'size' => $payload['size'],
            'params' => [
                'convert' => []
            ],
        ];
    }

    public function filter(array $payload, TaskManager $taskManager): array
    {
        $uuid = $taskManager->getUuid();
        $jobs = $taskManager->getPayloadKey('jobs');
        $completedJobs = $taskManager->getPayloadKey('completed_jobs');
        $taskFiles = $taskManager->getPayloadKey('files');

        $data = [
            'files' => [],
            'jobs' => $jobs,
            'completed_jobs' => $completedJobs,
        ];

        $payloadFiles = [];

        foreach ($payload['files'] as $file) {
            $payloadFiles[$file['hash']] = $file;
        }

        foreach ($taskFiles as $file) {
            if ($file['status'] == FileUploadHelper::FILE_STATUS_CREATED) {
                $fileData = $payloadFiles[$file['hash']];

                $fileName = FileUploadHelper::getFileName($fileData['hash'], $fileData['filename']);
                $fileArray = FileUploadHelper::getFileArray($uuid, $fileName);

                $params = [
                    'hash' => $fileData['hash'],
                    'status' => $fileData['status'],
                    'filename' => $fileData['filename'],
                    'extension' => $fileArray['extension'],
                    'mimetype' => $fileArray['mimetype'],
                    'size' => $fileArray['size'],
                    'params' => [
                        'convert' => [current($fileData['params']['convert'])] //TODO убрать, если планируется конвертация в несколько форматов
                    ],
                ];

                $data['jobs']++;
                $data['files'][] = $params;
            }
            else {
                $data['files'][] = $file;
            }
        }

        return $data;
    }

    public function prepare(array $payload, TaskManager $taskManager): array
    {
        $uuid = $taskManager->getUuid();
        $payload['task_id'] = $uuid;
        $fileService = new FileService($uuid);

        $fileFormats = Cache::remember('file_formats_with_categories', 3600, function () {
            return FileFormat::with('category:id,slug')
                ->get()
                ->pluck('category.slug', 'extension')
                ->toArray();
        });

        $payload['files'] = array_filter($payload['files'], function($file) {
            return $file['status'] == FileUploadHelper::FILE_STATUS_UPLOADED;
        });

        foreach ($payload['files'] as &$file) {
            $file['service_path'] = $fileService->getFilePathForService($file['hash']);
            //$file['service_path'] = FileUploadHelper::getFilePathForService($uuid, $file['hash'], $file['filename']);
            $file['file_type'] = $fileFormats[$file['extension']] ?? 'unknown';
        }

        return $payload;
    }
}
