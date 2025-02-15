<?php

namespace App\Adapters;

use App\Contracts\ServiceAdapterContract;
use App\Helpers\FileUploadHelper;
use App\Models\FileFormat;
use App\Models\Task;
use App\Services\TaskManager;
use Illuminate\Support\Facades\Cache;

class ConvertServiceAdapter implements ServiceAdapterContract
{

    public function filter(string $uuid, array $payload): array
    {
        $task = Task::getByUuid($uuid);
        $completedFiles = [];
        $jobs = 0;
        $completedJobs = 0;

        if ($task && $task->payload) {
            $completedFiles = $task->payload['files'];
            $jobs = $task->payload['jobs'];
            $completedJobs = $task->payload['completed_jobs'];
        }

        $data = [
            'files' => $completedFiles,
            'jobs' => $jobs,
            'completed_jobs' => $completedJobs,
        ];

        foreach ($payload['files'] as $file) {

            if ($file['status'] != FileUploadHelper::FILE_STATUS_UPLOADED) {
                continue;
            }

            $fileName = FileUploadHelper::getFileName($file['hash'], $file['filename']);
            $fileArray = FileUploadHelper::getFileArray($uuid, $fileName);
            //$fileFormat = FileFormat::where('extension', $fileArray['extension'])->with('category:id,slug')->first();

            $params = [
                'hash' => $file['hash'],
                'status' => $file['status'],
                'filename' => $file['filename'],
                'extension' => $fileArray['extension'],
                'mimetype' => $fileArray['mimetype'],
                'size' => $fileArray['size'],
                'params' => [
                    'convert' => [current($file['params']['convert'])] //TODO убрать, если планируется конвертация в несколько форматов
                ],
            ];
            $data['jobs']++;
            $data['files'][] = $params;
        }

        return $data;
    }

    public function prepare(array $payload, TaskManager $taskManager): array
    {
        $uuid = $taskManager->getUuid();
        $payload['task_id'] = $uuid;

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
            $file['service_path'] = FileUploadHelper::getFilePathForService($uuid, $file['hash'], $file['filename']);
            $file['file_type'] = $fileFormats[$file['extension']] ?? 'unknown';
        }

        return $payload;
    }
}
