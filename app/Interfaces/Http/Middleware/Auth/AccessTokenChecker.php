<?php

namespace App\Interfaces\Http\Middleware\Auth;

use Closure;
use Illuminate\Foundation\Application;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AccessTokenChecker
{
    private $app;
    private $authenticate;

    public function __construct(
        Application $app,
        Authenticate $authenticate
    ) {
        $this->app = $app;
        $this->authenticate = $authenticate;
    }

    public function handle($request, Closure $next)
    {
        if ($this->app->environment() !== 'testing') {
            try {
                return $this->authenticate->handle($request, $next, 'api');
            } catch (AuthenticationException $e) {
                throw new UnauthorizedHttpException('Challenge', 'invalid_token');
            }
        }

        return $next($request);
    }
}
