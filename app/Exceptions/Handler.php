<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException; // Importante
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    /**
     * Render an exception into an HTTP response.
     */
    public function render($request, Throwable $exception)
    {
        // Detectar error 419 (TokenMismatchException)
        if ($exception instanceof TokenMismatchException) {
            
            // Si es una petición AJAX/API, retornar JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Tu sesión ha expirado. Por favor, inicia sesión nuevamente.',
                    'redirect' => route('login')
                ], 419);
            }

            // Redirigir al login con mensaje flash
            return redirect()
                ->route('login')
                ->withInput($request->except('password', '_token'))
                ->with('error', 'Tu sesión ha expirado por inactividad. Por favor, inicia sesión nuevamente.');
        }

        return parent::render($request, $exception);
    }
}