<?php

namespace App\Providers;

use App\Registry\TaskRegistry;
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
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
