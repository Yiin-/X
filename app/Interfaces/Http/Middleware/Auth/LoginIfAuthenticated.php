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
        /**
         * If user has refresh token, refresh it now and try to login user.
         * We're doing it, so we can know if user is authenticated already,
         * before serving application back to the user.
         */
        if (request()->hasCookie('_accessToken')) {
            try {
                // $authData = $this->authService->attemptRefresh();

                $request->headers->set('Authorization', 'Bearer ' . request()->cookie('_accessToken'));

                return $this->authenticate->handle($request, $next, 'api');
            }
            catch(\Exception $e) {}
        }
        return $next($request);
    }
}
