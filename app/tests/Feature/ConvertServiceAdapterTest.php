<?php

namespace Tests\Feature;

use App\Adapters\ConvertServiceAdapter;
use Tests\TestCase;

class ConvertServiceAdapterTest extends TestCase
{
    public function test_filter_removes_invalid_convert_values()
    {
        $adapter = new ConvertServiceAdapter();

        $data = [
            'task' => 'adf52ebb-6ef2-4c7b-a067-c77facc36424',
            'payload' => [
                'files' => [
                    [
                        'hash' => '17b08508-1db8-4a73-91e5-b5b61836b316',
                        'status' => 4,
                        'filename' => '14mm (5).png',
                        'params' => [
                            'convert' => ['jpg'],
                        ],
                    ],
                ],
            ],
        ];

        $uuid = 'adf52ebb-6ef2-4c7b-a067-c77facc36424';

        $filteredPayload = $adapter->filter($uuid, $data['payload']);

        $this->assertEquals([
            'files' => [
                [
                    'hash' => '17b08508-1db8-4a73-91e5-b5b61836b316',
                    'status' => 4,
                    'filename' => '14mm (5).png',
                    'params' => [
                        'convert' => ['jpg'],
                    ],
                ],
            ],
        ], $filteredPayload);
    }
}
