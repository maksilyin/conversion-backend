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
    public function createFile(Request $request)
    {
        $request->validate([
            'task' => 'required|uuid',
            'hash' => 'required|uuid',
            'type' => 'required|string',
            'filename' => 'required|string',
            'size' => 'required|integer',
        ]);

        $task = $request->input('task');
        $taskType = $request->input('type');
        $taskManager = new TaskManager(null, $task);

        if (!$taskManager->isCanLoadFile()) {
            abort(422, 'Files cannot be uploaded while the task is in its current state.');
        }

        $payload = $this->taskServiceFactory
            ->createAdapter($taskType)
            ->create([
                'hash' => $request->input('hash'),
                'filename' => $request->input('filename'),
                'size' => $request->input('size'),
            ]);
        $taskManager->setStatus(Task::STATUS_CREATED);
        $taskManager->addFile($payload);

        return $payload['hash'];
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

        $taskManager->setPayloadData($payload);
        $taskManager->setStatus(Task::STATUS_PENDING);
        $taskManager->save();

        ProcessTaskJob::dispatch($taskManager->getUuid());

        return $taskManager->getUuid();
    }

    public function get($task_id)
    {
        return Task::getByUuid($task_id);
    }

    public function clear(Task $task): true
    {
        $taskManager = new TaskManager($task);
        $taskManager->clearTask();

        return true;
    }
}
