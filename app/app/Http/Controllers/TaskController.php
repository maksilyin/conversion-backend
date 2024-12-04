<?php

namespace App\Http\Controllers;

use App\Helpers\FileUploadHelper;
use App\Helpers\PrepareDataHelper;
use App\Jobs\ProcessTaskJob;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TaskController extends Controller
{
    public function create(): string
    {
        $taskId = (string) Str::uuid();
        $oTask = new Task();
        $oTask->setAttribute('uuid', $taskId);
        $oTask->save();

        return $oTask;
    }

    public function start(Request $request)
    {
        $request->validate([
            'payload' => 'required|array',
            'task' => 'required|uuid',
            'type' => 'required|string',
        ]);

        $taskUuid = $request->input('task');
        $oTask = Task::getByUuid($taskUuid);

        $taskId = $oTask->id;
        $type = $request->input('type');
        $payload = $request->input('payload');

        $payload = PrepareDataHelper::prepareDataToSave($payload, $taskUuid, $type, $oTask->payload);

        $oTask->fill([
            'status' => 'pending',
            'payload' => $payload,
            'type' => $type,
        ]);

        $oTask->save();

        ProcessTaskJob::dispatch($taskId);

        return $oTask->uuid;
    }

    public function get($task_id)
    {
        return Task::getByUuid($task_id);
    }
}
