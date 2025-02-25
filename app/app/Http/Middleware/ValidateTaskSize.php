<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class ValidateTaskSize
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $request->validate([
            'task' => 'required|uuid',
            'file' => 'required|file',
        ]);

        $taskId = $request->input('task');
        $chunk = $request->file('file');
        $maxTaskSize = intval(env('TASK_SIZE_LIMIT', 1000)) * 1024 * 1024; // Максимальный размер файла

        $uploadedSize = Cache::get("uploaded_size_{$taskId}", 0);
        $newUploadedSize = $uploadedSize + $chunk->getSize();

        // Если общий размер загруженных файлов превышает лимит
        if ($newUploadedSize > $maxTaskSize) {
            return response()->json([
                'error' => 'Total uploaded size exceeds the allowed limit',
                'limit' => $maxTaskSize,
                'usedLimit' => $newUploadedSize,
                'chunkSize' => $chunk->getSize(),
            ], 413);
        }

        // Обновляем кеш с новым размером
        Cache::put("uploaded_size_{$taskId}", $newUploadedSize, now()->addMinutes(120));

        return $next($request);
    }
}
