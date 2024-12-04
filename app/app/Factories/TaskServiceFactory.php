<?php

namespace App\Factories;

use App\Contracts\TaskContract;
use App\Services\ConvertService;
use InvalidArgumentException;

class TaskServiceFactory
{
    protected static array $taskServices = [
        'convert' => ConvertService::class,
    ];
    public static function make($type): TaskContract
    {
        $service = static::$taskServices[$type] ?? null;

        if (!$service) {
            throw new InvalidArgumentException("Unknown task type: {$type}");
        }

        return app($service);
    }
}
