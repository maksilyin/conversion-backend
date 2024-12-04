<?php

namespace App\Http\Middleware;

use App\Helpers\LocaleHelper;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->header('X-Accept-Language');

        if ($locale) {
            $languages = LocaleHelper::getActiveLocales();

            if (in_array($locale, $languages)) {
                app()->setLocale($locale);
            }
        }

        return $next($request);
    }
}
