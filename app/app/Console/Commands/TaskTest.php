<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Services\TaskCleaner;
use Illuminate\Console\Command;

class TaskTest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'task:test';

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
        $tasks = Task::where('uuid', '583c0ca9-4fc5-46f0-b7ec-f070bd0216f6')
            ->get();

        foreach ($tasks as $task) {
            $taskCleaner = new TaskCleaner($task);
            $taskCleaner->clear();
        }
    }
}
