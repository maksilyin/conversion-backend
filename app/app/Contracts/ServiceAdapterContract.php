<?php

namespace App\Contracts;

use App\Services\TaskManager;

interface ServiceAdapterContract
{
    /**
     * Фильтрует данные и возвращает валидный payload.
     *
     * @param string $uuid
     * @param array $payload
     * @return array
     */
    public function filter(string $uuid, array $payload): array;

    /**
     * Подготавливает данные для обработчика.
     *
     * @param string $uuid
     * @param array $payload
     * @return array
     */
    public function prepare(array $payload, TaskManager $taskManager): array;
}
