<?php

namespace App\Http\Controllers;

use App\Factories\TaskServiceFactory;
use App\Jobs\ProcessTaskJob;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TaskController extends Controller
{
    private TaskServiceFactory $taskServiceFactory;

    public function __construct(TaskServiceFactory $taskFactory)
    {
        $this->taskServiceFactory = $taskFactory;
    }
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
            'task' => 'required|uuid',
            'type' => 'required|string',
        ]);

        $taskType = $request->input('type');
        $taskValidator = $this->taskServiceFactory->createValidator($taskType);

        $request->validate([
            'payload' => ['required', 'array', $taskValidator],
        ]);

        $taskUuid = $request->input('task');
        $oTask = Task::getByUuid($taskUuid);

        $taskId = $oTask->id;
        $payload = $request->input('payload');

        $payload = $this->taskServiceFactory->createAdapter($taskType)->filter($taskUuid, $payload);

        $oTask->fill([
            'status' => 'pending',
            'payload' => $payload,
            'type' => $taskType,
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
