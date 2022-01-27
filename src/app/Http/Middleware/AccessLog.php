<?php

namespace App\Http\Middleware;

use Closure;
use Log;

class AccessLog
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        Log::info('REQUEST CALLED: '.$request->fullUrl());
        return $next($request);
    }
}
