<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckFileType
{
    private $forbiddenMimeTypes = [
        'application/x-php',
        'text/x-php',
        'application/x-sh',
        'application/javascript',
    ];
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $chunkIndex = $request->input('index');

        if ($request->hasFile('file') && $chunkIndex == 1) {
            $file = $request->file('file');

            $chunk = file_get_contents($file->getRealPath(), false, null, 0, 1024);

            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->buffer($chunk);

            if (in_array($mimeType, $this->forbiddenMimeTypes)) {
                return response()->json(['error' => 'Uploading this file type is not allowed'], 400);
            }
        }

        return $next($request);
    }
}
