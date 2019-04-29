<?php

namespace App\Http\Middleware;

use App\Traits\Http\JsonResponseTrait;
use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class CheckIsMyUserMiddleware
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
        return User::canAccessResource(
            empty($request->route()[2]['id']) ? null : $request->route()[2]['id'],
            Auth::user()->id
        ) ? $next($request) : static::buildUnauthorizedResponse();
    }
}
