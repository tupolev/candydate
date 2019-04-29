<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\UnauthorizedException;

class CheckClientCredentials extends \Laravel\Passport\Http\Middleware\CheckClientCredentials
{
    public function handle($request, Closure $next, ...$scopes)
    {
        try {
            return parent::handle($request, $next, $scopes);
        } catch (AuthenticationException $ex) {
            throw new UnauthorizedException('Unauthorized');
        }
    }
}
