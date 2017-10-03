<?php

namespace App\Interfaces\Http\Middleware\Auth;

use Closure;
use Illuminate\Foundation\Application;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use App\Domain\Service\Auth\AuthService;

class LoginIfAuthenticated
{
    private $app;
    private $authenticate;
    private $authService;

    public function __construct(
        Application $app,
        Authenticate $authenticate,
        AuthService $authService
    ) {
        $this->app = $app;
        $this->authenticate = $authenticate;
        $this->authService = $authService;
    }

    public function handle($request, Closure $next)
    {
        if (request()->hasCookie('_accessToken')) {
            $accessToken = request()->cookie('_accessToken');
            try {
                $request->headers->set('Authorization', 'Bearer ' . $accessToken);

                return $this->authenticate->handle($request, $next, 'api');
            }
            catch(\Exception $e) {
                \Log::error($e->getMessage());
            }
        }
        return $next($request);
    }
}
