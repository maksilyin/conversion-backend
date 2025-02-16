<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Services\TaskCleaner;
use Illuminate\Console\Command;

class ClearExpiredTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:clear-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expirationTime = env('TASK_EXPIRATION_TIME', 360);

        if (!is_numeric($expirationTime) || intval($expirationTime) <= 0) {
            throw new \InvalidArgumentException("Некорректное значение TASK_EXPIRATION_TIME: '{$expirationTime}'. Укажите число больше 0.");
        }

        $tasks = Task::where('created_at', '<', now()->subMinutes($expirationTime))
            ->where('status', Task::STATUS_LOCK)
            ->get();

        foreach ($tasks as $task) {
            $taskCleaner = new TaskCleaner($task);
            $taskCleaner->clear();
        }
    }
}
