<?php

namespace App\Http\Middleware;

use App\Models\Task;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
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
        $taskId = $request->route('task') ?? $request->input('task') ;

        if (!$taskId || !Str::isUuid($taskId) || !Task::isExists($taskId)) {
            return response()->json(['error' => 'Invalid or missing task UUID'], 400);
        }

        return $next($request);
    }
}
