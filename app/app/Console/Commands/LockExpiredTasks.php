<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Services\TaskCleaner;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class LockExpiredTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:lock-expired';

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

        if ($expirationTime > 60) {
            $expirationTime -= 60;
        }
        else {
            $expirationTime = $expirationTime / 2;
        }

        $lockedCount = Task::where('created_at', '<', now()->subMinutes($expirationTime))
            ->whereNotIn('status', [Task::STATUS_CLEAR, Task::STATUS_LOCK])
            ->update(['status' => Task::STATUS_LOCK]);
    }
}
