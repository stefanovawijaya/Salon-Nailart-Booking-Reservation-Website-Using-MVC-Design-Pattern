<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Auth;

class ViewServiceProvider extends ServiceProvider
{
    public function boot()
    {
        View::composer('layouts.salon', function ($view) {
            $salon = Auth::guard('salon')->user();
            $view->with('salon', $salon);
        });
    }

    public function register()
    {
        //
    }
}

