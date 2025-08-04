<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Exceptions\ThrottleRequestsException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {

        $exceptions->renderable(function (ThrottleRequestsException $e, $request) {

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'تلاش‌های شما بیش از حد مجاز است. لطفاً پس از یک دقیقه دوباره امتحان کنید.',
                    'errors' => [
                        'username' => ['تلاش‌های شما بیش از حد مجاز است. لطفاً پس از یک دقیقه دوباره امتحان کنید.']
                    ]
                ], 429);
            }

            return redirect()->route('login')
                ->withErrors(['username' => 'تلاش‌های شما بیش از حد مجاز است. لطفاً پس از یک دقیقه دوباره امتحان کنید.'])
                ->withInput($request->only('username'));
        });
    })->create();
