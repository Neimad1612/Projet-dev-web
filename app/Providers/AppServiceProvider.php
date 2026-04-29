<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\User;
use App\Observers\UserObserver;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void { }

    public function boot(): void {

        User::observe(UserObserver::class);
        Paginator::useBootstrap();
    }
}