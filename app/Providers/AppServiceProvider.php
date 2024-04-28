<?php

namespace App\Providers;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Database\Events\MigrationsEnded;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

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
        Event::listen(MigrationsEnded::class, function () {
            $this->app[Kernel::class]->call('db:seed', ['--class' => 'SuperuserSeeder']);
        });
    }
}
