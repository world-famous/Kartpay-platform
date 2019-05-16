<?php

namespace App\Providers;

use App\Classes\Database\DatabaseManager;
use Illuminate\Support\ServiceProvider;

class DatabaseServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('db', function ($app)
        {
            return new DatabaseManager($app, $app['db.factory']);
        });
    }
}
