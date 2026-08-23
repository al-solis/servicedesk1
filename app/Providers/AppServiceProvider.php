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
use Illuminate\Support\Facades\Cookie;
use App\Models\TicketDetail;
use App\Models\TicketHeader;

use App\Observers\TicketDetailObserver;
use App\Observers\TicketHeaderObserver;


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

        // Cookie::defaults(function () {
        //     return cookie()->makeDefaults()
        //         ->setHttpOnly(true)
        //         ->setSecure(true)
        //         ->setSameSite('lax');
        // });


        View::composer('layouts.navbar', NavbarComposer::class);
        TicketHeader::observe(TicketHeaderObserver::class);
        TicketDetail::observe(TicketDetailObserver::class);
    }
}
