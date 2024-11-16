<?php

namespace App\Contracts;

interface TaskContract
{
    public function execute(array $payload);
}
