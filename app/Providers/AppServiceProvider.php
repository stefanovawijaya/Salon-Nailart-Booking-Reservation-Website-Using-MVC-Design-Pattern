<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Session;
use App\Models\Salon;

class AppServiceProvider extends ServiceProvider
{
    
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        View::composer('*', function ($view) {
            if (!$view->offsetExists('salon')) {
                $salon = null;
                if (Session::has('salon')) {
                    $salon_id = Session::get('salon.salon_id');
                    $salon = Salon::find($salon_id);
                }
                $view->with('salon', $salon);
            }
        });
    }
}
