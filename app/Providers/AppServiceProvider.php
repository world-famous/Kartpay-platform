<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;

use App\PasswordLogs;
use Hash;

use App\Merchant;
use App\Observers\MerchantObserver;

use App\User;
use App\Observers\UserObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        Validator::extend('alpha_spaces', function ($attribute, $value, $parameters, $validator)
        {
            return preg_match('/^[\pL\s]+$/u', $value);
        });

        Validator::extend('last_pass', function($attribute, $value, $parameters, $validator)
        {
            $logs = PasswordLogs::where('user' , md5($parameters[0]))
                            ->orderBy('created_at', 'DESC')
                            ->limit(5)
                            ->get();

            if(count($logs) > 0)
            {
                foreach($logs as $log)
                {
                    if(Hash::check($value, $log->password))
                    {
                        return false;
                    }
                }
            }
            return true;
        });

        Validator::replacer('last_pass', function($message, $attribute, $rule, $parameters)
        {
            return str_replace(':field', $parameters[0], $message);
        });

        Merchant::observe(new MerchantObserver());
        User::observe(new UserObserver());
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment('local'))
        {
            $this->app->register(\Barryvdh\Debugbar\ServiceProvider::class);
        }

        if (getLiveEnv('hasToSetupDb') !== "0")
        {
            config(['session.driver' => 'file']);
        }

        $this->app->alias('bugsnag.logger', \Illuminate\Contracts\Logging\Log::class);
        $this->app->alias('bugsnag.logger', \Psr\Log\LoggerInterface::class);
    }
}
