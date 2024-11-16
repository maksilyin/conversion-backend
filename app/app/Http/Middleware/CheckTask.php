<?php

namespace App\Http\Middleware;

use App\Models\Task;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class CheckTask
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $uuid = $request->route('task');

        if (!$uuid || !Str::isUuid($uuid) || !Task::isExists($uuid)) {
            return response()->json(['error' => 'Invalid or missing task UUID'], 400);
        }

        return $next($request);
    }
}
