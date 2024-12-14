<?php

use App\Adapters\ConvertServiceAdapter;
use App\Rules\ConvertPayloadRule;
use App\Services\ConvertService;

return [
    'convert' => [
        'validator' => ConvertPayloadRule::class,
        'handler' => ConvertService::class,
        'adapter' => ConvertServiceAdapter::class,
    ]
];
