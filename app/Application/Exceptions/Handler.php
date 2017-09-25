<?php

namespace App\Application\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Debug\Exception\FatalThrowableError;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        \League\OAuth2\Server\Exception\OAuthServerException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof ValidationException)
        {
            return response()->json([
                'error' => true,
                'messages' => collect($exception->validator->errors())->map(function ($error) {
                    return $error[0];
                })
            ], 422);
        }

        if ($exception instanceof UnprocessableEntityHttpException) {
            return response()->json([
                'error' => true,
                'messages' => collect(json_decode($exception->getMessage()))->map(function ($error) {
                    return $error[0];
                })
            ], 422);
        }

//        if ($exception instanceof AuthorizationException) {
//            return response($exception->getMessage(), 401);
//        }

        if ($exception instanceof FatalThrowableError) {
            return parent::render($request, $exception);
        }
        return parent::render($request, $exception);
    }
}
