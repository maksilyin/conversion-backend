<?php

namespace App\Providers;

use App\Registry\TaskRegistry;
use App\Services\TaskManager;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class TaskServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(TaskRegistry::class, function ($app) {
            $taskRegistry = new TaskRegistry();
            $tasks = config('tasks');

            foreach ($tasks as $type => $config) {
                $taskRegistry->registerTask($type, $config);
            }

            return $taskRegistry;
        });
        $this->app->bind(TaskManager::class, function ($app) {
            $request = $app->make(Request::class);
            $task = $request->route('task') ?? $request->input('task') ;
            return new TaskManager(null, $task);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
