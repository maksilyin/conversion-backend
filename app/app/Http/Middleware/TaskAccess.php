<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class TaskAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $taskId = $request->route('task') ?? $request->input('task');
        Log::info('TaskAccess', [$taskId]);
        $accessible = $request->session()->get('accessible_tasks', []);

        if (app()->environment('production') && !in_array($taskId, $accessible)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return $next($request);
    }
}
