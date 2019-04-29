<?php

namespace App\Http\Middleware;

use App\Models\JobProcess;
use App\Traits\Http\JsonResponseTrait;
use Closure;
use Illuminate\Support\Facades\Auth;

class CheckIsMyJobProcessMiddleware
{
    use JsonResponseTrait;

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return JobProcess::jobProcessBelongsToUser(
            empty($request->route()[2]['jobProcessId']) ? null : $request->route()[2]['jobProcessId'],
            Auth::user()->id
        ) ? $next($request) : static::buildUnauthorizedResponse();
    }
}
