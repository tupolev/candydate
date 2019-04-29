<?php

namespace App\Http\Middleware;

use App\Models\JobProcessContact;
use App\Traits\Http\JsonResponseTrait;
use Closure;
use Illuminate\Support\Facades\Auth;

class CheckIsMyJobProcessContactMiddleware
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
        return JobProcessContact::jobProcessContactBelongsToJobProcess(
            empty($request->route()[2]['jobProcessId']) ? null : $request->route()[2]['jobProcessId'],
            empty($request->route()[2]['jobProcessContactId']) ? null : $request->route()[2]['jobProcessContactId']
        ) ? $next($request) : static::buildUnauthorizedResponse();
    }
}
