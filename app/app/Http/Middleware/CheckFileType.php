<?php

namespace App\Http\Middleware;

use App\Exceptions\FileUploadException;
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
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     * @throws FileUploadException
     */
    public function handle(Request $request, Closure $next): Response
    {
        $chunkIndex = $request->input('index');
        $hash = $request->input('hash');

        if ($request->hasFile('file') && $chunkIndex == 1) {
            $file = $request->file('file');

            $chunk = file_get_contents($file->getRealPath(), false, null, 0, 1024);

            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->buffer($chunk);

            if (in_array($mimeType, $this->forbiddenMimeTypes)) {
                throw new FileUploadException('Uploading this file type is not allowed', 400, $hash);
            }
        }

        return $next($request);
    }
}
