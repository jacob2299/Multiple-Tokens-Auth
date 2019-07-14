<?php

namespace MultipleTokenAuth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class MultipleTokenAuthServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
            $this->publishes([
                __DIR__.'/../config/multiple-tokens-auth.php' => config_path('multiple-tokens-auth.php'),
            ]);
        }

        Auth::extend('multiple-tokens', function ($app, $name, array $config) {
            return new MultipleTokensGuard(
                Auth::createUserProvider($config['provider']),
                $app->make('request')
            );
        });
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/multiple-tokens-auth.php', 'multiple-tokens-auth');
    }
}
