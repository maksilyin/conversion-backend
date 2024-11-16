<?php

namespace App\Http\Controllers;

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

        return $taskId;
    }

    public function start(Request $request): string
    {
        $request->validate([
            'payload' => 'required|array',
            'task' => 'required|uuid',
            'type' => 'required|string',
        ]);

        $taskId = $request->input('task');
        $oTask = Task::where('uuid', $taskId)->firstOrFail();

        $taskId = $oTask->id;
        $type = $request->input('type');
        $payload = $request->input('payload');

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
        $task = Task::where('uuid', $task_id)->select(['id', 'uuid', 'status', 'type', 'payload'])->firstOrFail();

        return $task;
    }
}
