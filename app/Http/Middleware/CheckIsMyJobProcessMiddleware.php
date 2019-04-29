<?php

namespace App\Http\Middleware;

use App\JobProcess;
use App\Traits\Http\JsonResponseTrait;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckIsMyJobProcessMiddleware
{
    use JsonResponseTrait;

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return JobProcess::isJobProcessFromUser(
            empty($request->route()[2]['id']) ? null : $request->route()[2]['id'],
            Auth::user()->id
        ) ? $next($request) : static::buildUnauthorizedResponse();
    }
}
