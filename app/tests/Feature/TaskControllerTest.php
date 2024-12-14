<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    public function test_handle_valid_payload()
    {
        $payload = [
            'task' => 'adf52ebb-6ef2-4c7b-a067-c77facc36424',
            'type' => 'convert',
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

        $response = $this->putJson('/api/task/', $payload);

        $response->assertStatus(200)
            ->assertSee('adf52ebb-6ef2-4c7b-a067-c77facc36424');
    }

    public function test_handle_invalid_payload()
    {
        $payload = [
            'task' => 'adf52ebb-6ef2-4c7b-a067-c77facc36424',
            'type' => 'convert',
            'payload' => [
                'files' => [
                    [
                        'hash' => '17b08508-1db8-4a73-91e5-b5b61836b316',
                        'status' => 4,
                        'filename' => '14mm (5).png',
                        'params' => [
                            'convert' => ['exe'],
                        ],
                    ],
                ],
            ],
        ];

        $response = $this->putJson('/api/task/', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('payload');
    }

    public function test_handle_empty_type()
    {
        $payload = [
            'task' => 'adf52ebb-6ef2-4c7b-a067-c77facc36424',
            'payload' => [
                'files' => [
                    [
                        'hash' => '17b08508-1db8-4a73-91e5-b5b61836b316',
                        'status' => 4,
                        'filename' => '14mm (5).png',
                        'params' => [
                            'convert' => ['exe'],
                        ],
                    ],
                ],
            ],
        ];

        $response = $this->putJson('/api/task/', $payload);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('type');
    }
}
