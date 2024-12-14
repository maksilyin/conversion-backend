<?php

namespace Tests\Unit;

use App\Registry\TaskRegistry;
use PHPUnit\Framework\TestCase;

class TaskRegistryTest extends TestCase
{
    public function testRegisterTask()
    {
        $registry = new TaskRegistry();

        $taskConfig = [
            'validator' => 'App\Rules\ConvertPayloadRule',
            'handler' => 'App\Services\ConvertService',
            'adapter' => '',
        ];

        $registry->registerTask('convert', $taskConfig);

        $this->assertEquals($taskConfig, $registry->getConfig('convert'));
    }

    public function testNonexistentTaskThrowsException()
    {
        $this->expectException(\Exception::class);

        $registry = new TaskRegistry();
        $registry->getConfig('nonexistent');
    }
}
