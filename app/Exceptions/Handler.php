<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    public function render($request, Throwable $exception)
    {
        if ($request->expectsJson()) {
            if ($exception instanceof AuthenticationException) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }

            if ($exception instanceof ValidationException) {
                return response()->json([
                    'message' => 'The given data was invalid.',
                    'errors' => $exception->errors(),
                ], 422);
            }

            if ($exception instanceof NotFoundHttpException) {
                return response()->json(['message' => 'Not Found.'], 404);
            }

            return response()->json(['message' => 'Server Error.'], 500);
        }

        return parent::render($request, $exception);
    }
}