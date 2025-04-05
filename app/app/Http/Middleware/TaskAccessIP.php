<?php

namespace App\Http\Middleware;

use App\Services\TaskManager;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

class TaskAccessIP
{
    protected TaskManager $taskManager;

    public function __construct(TaskManager $taskManager)
    {
        $this->taskManager = $taskManager;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $ip = $request->header('X-Forwarded-For')
            ? trim(explode(',', $request->header('X-Forwarded-For'))[0])
            : $request->ip();


        if (!$this->taskManager->isIP($ip)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return $next($request);
    }
}
