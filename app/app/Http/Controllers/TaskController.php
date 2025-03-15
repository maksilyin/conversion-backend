<?php

namespace App\Http\Controllers;

use App\Factories\TaskServiceFactory;
use App\Jobs\ProcessTaskJob;
use App\Models\Task;
use App\Services\TaskManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TaskController extends Controller
{
    private TaskServiceFactory $taskServiceFactory;

    public function __construct(TaskServiceFactory $taskFactory)
    {
        $this->taskServiceFactory = $taskFactory;
    }
    public function create(Request $request): string
    {
        $request->validate([
            'type' => 'required|string',
        ]);
        $taskType = $request->input('type');

        if (!$this->taskServiceFactory->hasTaskService($taskType)) {
            abort(400);
        }

        $taskId = (string) Str::uuid();
        $oTask = new Task();

        $initPayload = [
            'jobs' => 0,
            'completed_jobs' => 0,
        ];

        $oTask->setAttribute('type', $taskType);
        $oTask->setAttribute('uuid', $taskId);
        $oTask->setAttribute('payload', $initPayload);
        $oTask->save();

        return $oTask;
    }

    /**
     * @throws \Exception
     */
    public function start(Request $request)
    {
        $request->validate([
            'task' => 'required|uuid',
            'type' => 'required|string',
        ]);

        $taskType = $request->input('type');
        $taskUuid = $request->input('task');
        $taskManager = new TaskManager(null, $taskUuid, false);

        $taskValidator = $this->taskServiceFactory->createValidator($taskType, $taskManager);

        $request->validate([
            'payload' => ['required', 'array', $taskValidator],
        ]);

        if ($taskManager->status() == Task::STATUS_LOCK) {
            abort(423, 'Task is locked and cannot be started.');
        }

        if ($taskManager->status() === Task::STATUS_CLEAR) {
            abort(400, 'Task has been cleared and cannot be restarted.');
        }

        if ($taskManager->status() === Task::STATUS_PENDING) {
            abort(409, 'Task is already pending.');
        }

        $payload = $request->input('payload');

        $payload = $this->taskServiceFactory->createAdapter($taskType)->filter($payload, $taskManager);

        \Illuminate\Support\Facades\Log::info('start setPayloadData');
        $taskManager->setPayloadData($payload);
        \Illuminate\Support\Facades\Log::info('start setStatus');
        $taskManager->setStatus(Task::STATUS_PENDING);
        \Illuminate\Support\Facades\Log::info('start save');
        $taskManager->save();

        ProcessTaskJob::dispatch($taskManager->getUuid());

        return $taskManager->getUuid();
    }

    public function get(string $task)
    {
        return Task::getForResult($task);
    }

    public function clear(Task $task): true
    {
        $taskManager = new TaskManager($task);
        $taskManager->clearTask();

        return true;
    }
}
