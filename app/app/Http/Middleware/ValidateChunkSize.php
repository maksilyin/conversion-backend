<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class ValidateChunkSize
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $fileIdentifier = $request->input('hash');
        $chunk = $request->file('file');
        $totalSize = $request->input('size'); // Общий размер файла
        $maxFileSize = 100 * 1024 * 1024; // Максимальный размер файла (100MB)

        if (!$chunk || !$fileIdentifier || !$totalSize) {
            return response()->json(['error' => 'Invalid upload data provided'], 400);
        }

        if ($totalSize > $maxFileSize) {
            return response()->json(['error' => 'File size exceeds the maximum limit'], 400);
        }

        // Получаем текущий загруженный размер из кеша
        $uploadedSize = Cache::get("uploaded_size_{$fileIdentifier}", 0) + $chunk->getSize();

        if ($uploadedSize > $maxFileSize) {
            return response()->json(['error' => 'File exceeds the maximum allowed size'], 413);
        }

        if ($uploadedSize > $totalSize) {
            return response()->json(['error' => 'Total file size mismatch'], 400);
        }

        // Сохраняем прогресс
        Cache::put("uploaded_size_{$fileIdentifier}", $uploadedSize, now()->addMinutes(30));

        return $next($request);
    }
}
