<?php

namespace App\Exceptions;

use Exception;
use Throwable;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Laravel\Passport\Exceptions\OAuthServerException;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->renderable(function (Throwable $exception, $request) {
            // set default error code
            $code = method_exists($exception, 'getStatusCode') ? $exception->getStatusCode() : 500;
            $error = $exception->getMessage();

            // handle API exceptions
            if ($request->expectsJson()) {
                // expired / has no valid token
                if ($exception instanceof AuthenticationException) {
                    $code = 401;
                }

                // expired / has no valid token
                if ($exception instanceof ValidationException) {
                    $error = $exception->errors();
                    $code = 422;
                }

                // laravel passport error
                if ($exception instanceof OAuthServerException) {
                    $code = $exception->statusCode();
                }

                // ACL Exception error
                if ($exception instanceof UnauthorizedException) {
                    $error = __('exception.unauthorized');
                    $code = 403;
                }

                return response()->json(
                    [
                        'code' => $code,
                        'error' => $error,
                    ],
                    $code
                );
            }
        });
    }
}
