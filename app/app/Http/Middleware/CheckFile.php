<?php

namespace App\Http\Middleware;

use App\Models\File;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class CheckFile
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $hash = $request->input('hash');

        if (!$hash || !Str::isUuid($hash) || !File::isExists($hash)) {
            return response()->json(['error' => 'Invalid or missing file id'], 400);
        }

        return $next($request);
    }
}
