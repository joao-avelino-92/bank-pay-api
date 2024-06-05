<?php

namespace App\Providers;

use App\Models\User;
use App\Observers\UserObserver;
use Dusterio\LumenPassport\LumenPassport;
use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        LumenPassport::routes($this->app);
    }

    public function boot(){
        User::observe(UserObserver::class);
        Passport::ignoreMigrations();
        \Dusterio\LumenPassport\LumenPassport::routes($this->app);
    }

}
