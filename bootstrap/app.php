<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
        ]);

        $middleware->alias([
            'auth.client' => \Illuminate\Auth\Middleware\Authenticate::class . ':client',
            'guest.client' => \Illuminate\Auth\Middleware\RedirectIfAuthenticated::class . ':client',
            
            'auth.salon' => \App\Http\Middleware\SalonAuth::class,
            
            'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
            'guest' => \Illuminate\Auth\Middleware\RedirectIfAuthenticated::class,
            
            'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
            
            'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
            
            'csrf' => \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
            
            'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        ]);

        $middleware->redirectGuestsTo(function ($request) {
            if ($request->is('salon/home') || 
                $request->is('salon') ||
                $request->is('salon/edit') || 
                $request->is('salon/update') ||
                $request->is('gallery/add') ||
                $request->is('gallery/edit/*') ||
                $request->is('gallery/update') ||
                $request->is('gallery/delete') ||
                $request->is('treatment/*') ||
                $request->is('voucher/*') ||
                $request->is('schedule') ||
                $request->is('schedule/save') ||
                $request->is('salon/schedule/*')) {
                return route('login.salon');
            }
            
            if ($request->is('client/*') || 
                $request->is('reservation/*')) {
                return route('login');
            }
            
            return route('login');
        });

        $middleware->redirectUsersTo(function ($request) {
            if ($request->is('login') || $request->is('register')) {
                if (auth()->guard('client')->check()) {
                    return route('home');
                }
            }
            
            if ($request->is('login/salon') || $request->is('register/salon')) {
                if (auth()->guard('salon')->check()) {
                    return route('salon.home');
                }
            }
            
            return null;
        });

        $middleware->group('web', [
            \Illuminate\Cookie\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        $middleware->group('api', [
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        $middleware->group('client', [
            'web',
            'auth.client',
        ]);

        $middleware->group('salon', [
            'web', 
            'auth.salon',
        ]);

        $middleware->group('guest.client', [
            'web',
            'guest.client',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthenticated.'], 401);
            }

            $path = $request->path();
            
            if (str_starts_with($path, 'salon/') || 
                str_starts_with($path, 'gallery/') ||
                str_starts_with($path, 'treatment/') ||
                str_starts_with($path, 'voucher/') ||
                str_starts_with($path, 'schedule')) {
                return redirect()->route('login.salon');
            }
            
            if (str_starts_with($path, 'client/') || 
                str_starts_with($path, 'reservation/')) {
                return redirect()->route('login');
            }
            
            $guards = $e->guards();
            if (in_array('salon', $guards)) {
                return redirect()->route('login.salon');
            }
            
            return redirect()->route('login');
        });
    })
    ->create();