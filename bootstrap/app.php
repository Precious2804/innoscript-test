<?php

use App\Http\Middleware\ForceJsonResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use ProtoneMedia\LaravelXssProtection\Middleware\XssCleanInput;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(ForceJsonResponse::class);
    })
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(XssCleanInput::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (AuthenticationException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 401);
        });
        $exceptions->render(function (NotFoundHttpException $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 401);
        });
    })->create();
