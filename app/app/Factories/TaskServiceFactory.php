<?php

namespace App\Factories;

use App\Contracts\TaskContract;
use App\Services\ConvertService;

class TaskServiceFactory
{
    public static function make($type): TaskContract
    {
        return match ($type) {
            'convert' => new ConvertService(),
            default => throw new \InvalidArgumentException("Unknown task type: {$type}"),
        };
    }
}
