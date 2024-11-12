<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
//        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function (){
            \Illuminate\Support\Facades\Route::middleware(['api'])
                ->middleware(\App\Http\Middleware\LocalizationMiddleware::class)

                ->prefix('api/{locale}')

                ->group(__DIR__.'/../routes/api.php');
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {

        $exceptions->shouldRenderJsonWhen(function (\Illuminate\Http\Request $request, Throwable $e) {
            if ($request->is('api/*')) {
                return true;
            }

            return $request->expectsJson();
        });
        $exceptions->report(function (\Illuminate\Auth\AuthenticationException $e) {
            return response()->json([
                'message' => 'Not authenticated'
            ], 401);
        });
    })->create();
