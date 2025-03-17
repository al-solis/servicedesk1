<?php

namespace App\Providers;
use App\Http\ViewComposers\NavbarComposer;
use Illuminate\Foundation\Console\Kernel;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illumante\Auth\middleware\RedirectIfAuthenticated;
use Illuminate\Auth\Middleware\Authenticate;
use Illumate\Support\Facades\Session;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // View::composer('*', function($view)
        // {
        //     $view->with('authUser', Auth:user());
        // });        

        View::composer('layouts.navbar', NavbarComposer::class);
    }


}
