<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SalonAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::guard('salon')->check()) {
            return redirect()->route('login.salon');
        }

        return $next($request);
    }
}