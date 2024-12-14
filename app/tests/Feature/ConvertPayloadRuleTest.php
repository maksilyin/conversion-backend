<?php

namespace Tests\Feature;

use App\Rules\ConvertPayloadRule;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class ConvertPayloadRuleTest extends TestCase
{
    public function testValidPayloadPasses()
    {
        $rule = new ConvertPayloadRule();

        $data = [
            'payload' => [
                'files' => [
                    ['hash' => 'abc123', 'filename' => 'file1.txt', 'params' => ['convert' => ['jpg']]],
                    ['hash' => 'abc123', 'filename' => 'file1.txt', 'params' => ['convert' => ['png', 'pdf']]],
                ],
            ]
        ];

        $validator = Validator::make($data, [
            'payload' => ['required', 'array', $rule],
        ]);

        $this->assertTrue($validator->passes());
    }

    public function testInvalidPayloadFailsWithoutFiles()
    {
        $rule = new ConvertPayloadRule();

        $data = [
            'payload' => [
                'no_files' => [],
            ]
        ];

        $validator = Validator::make($data, [
            'payload' => ['required', 'array', $rule],
        ]);

        $this->assertFalse($validator->passes());
    }

    public function testInvalidPayloadFailsWithIncompleteFileData()
    {
        $rule = new ConvertPayloadRule();

        $data = [
            'payload' => [
                'files' => [
                    ['hash' => 'abc123'],
                    ['filename' => 'file2.txt'],
                ],
            ]
        ];

        $validator = Validator::make($data, [
            'payload' => ['required', 'array', $rule],
        ]);

        $this->assertFalse($validator->passes());
    }

    public function testInvalidParams()
    {
        $rule = new ConvertPayloadRule();

        $data = [
            'payload' => [
                'files' => [
                    ['hash' => 'abc123', 'filename' => 'file1.txt', 'params' => []],
                    ['hash' => 'abc123', 'filename' => 'file1.txt', 'params' => ['convert' => ['pngh']]],
                ],
            ]
        ];

        $validator = Validator::make($data, [
            'payload' => ['required', 'array', $rule],
        ]);

        $this->assertFalse($validator->passes());
    }
}
