<?php

namespace App\Http\Controllers;

use App\Factories\TaskServiceFactory;
use App\Helpers\FileUploadHelper;
use App\Jobs\ProcessTaskJob;
use App\Models\FileFormat;
use App\Models\Task;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TestFormatController
{
    private TaskServiceFactory $taskServiceFactory;
    public function __construct(TaskServiceFactory $taskFactory)
    {
        $this->taskServiceFactory = $taskFactory;
    }
    public function test()
    {
        $taskUuid = 'bc71cc5e-d3a4-4ce2-9035-b96060b40c1c';
        $formats = FileFormat::where('category_id', 2)->get()->toArray();

        $directory = '/app/storage/app/uploads/test/';
        $files = glob($directory . '*');
        $oTask = Task::getByUuid($taskUuid);

        $arFiles = [];

        foreach ($files as $key => $file) {
            if (!is_file($file)) continue;
            $convert = [];
            $extension = pathinfo($file, PATHINFO_EXTENSION);
            //$fileHash = (string) Str::uuid();
            $fileHash = $key;

            $originalFileName = FileUploadHelper::getFileName($fileHash, basename($file));
            $finalPath = FileUploadHelper::getDir($taskUuid);

            if (!file_exists($finalPath)) {
                mkdir($finalPath, 0755, true);
            }

            if (!copy($file, $finalPath . $originalFileName)) {
                echo "Ошибка при копировании файла ".$file . PHP_EOL;
                continue;
            }

            foreach ($formats as $format) {
                if ($format['extension'] === $extension) continue;
                $convert[] = $format['extension'];
            }

            $arFiles[] = [
                'filename' => basename($file),
                'hash' => $fileHash,
                'params' => [
                    'convert' => $convert
                ],
                'status' => 4
            ];
        }

        $payload = [
            'files' => $arFiles
        ];

        echo "<pre>";
        print_r($payload);
        echo "</pre>";

        $taskType = 'convert';
        $taskId = $oTask->id;
        $payload = $this->taskServiceFactory->createAdapter($taskType)->filter($taskUuid, $payload);

        $oTask->fill([
            'status' => 'pending',
            'payload' => $payload,
            'type' => $taskType,
        ]);

        $oTask->save();
        echo 'Запускаю задачу'.PHP_EOL;
        ProcessTaskJob::dispatch($taskId);
    }

    public function testTask()
    {
        $taskUuid = 'bc71cc5e-d3a4-4ce2-9035-b96060b40c1c';
        $oTask = Task::getByUuid($taskUuid);

        $payload = $oTask->payload;
        echo 'Статус: '.$oTask->status."<br>";
        foreach ($payload['files'] as $arFile) {
            echo $arFile['filename'].':'."<br>";
            echo $arFile['extension'].':'."<br>";
            echo "<ul>";
            foreach ($arFile['result'] as $result) {
                echo "<li>";
                echo '<ul>';
                    if (isset($result['filename'])) {
                        echo "<li>";
                        echo "Файл: " . $result['filename'];
                        echo "</li>";
                    }

                    if (isset($result['extension'])) {
                        echo "<li>";
                        echo "extension: <span style='color: green'>" . $result['extension'] . "</span>";
                        echo "</li>";
                    }

                    if (isset($result['status'])) {
                        echo "<li>";
                        echo "STATUS: " . $result['status'];
                        echo "</li>";
                    }

                    if (isset($result['error'])) {
                        echo "<li>";
                        echo "ERROR: <span style='color: red'>" . $result['error'] . "</span>";
                        echo "</li>";
                    }

                echo '</ul>';
                echo "</li>";
            }
            echo "</ul>";
        }
    }

    public function event(): true
    {
        $taskUuid = 'ed8d1574-edc1-4e01-abe9-ee21a2280b80';
        $oTask = Task::getByUuid($taskUuid);
        Broadcast::channel('task.' . $oTask->uuid, function () {
            return true;
        });

        broadcast(new \App\Events\TaskUpdated($oTask));

        return true;
    }
}
