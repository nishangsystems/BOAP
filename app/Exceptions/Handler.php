<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
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
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        // Laravel wraps CSRF token mismatches in an HTTP 419 before rendering.
        $this->renderable(function (HttpException $e, $request) {
            if ($e->getStatusCode() !== 419 || ! ($e->getPrevious() instanceof TokenMismatchException)) {
                return null;
            }

            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => 'Your session has expired. Please try again.',
                ], 419);
            }

            return redirect()->route('login')
                ->with('error', 'Your session expired. Please try logging in again.');
        });
        
    }
}
