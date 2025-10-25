<?php

namespace Tuantq\LaravelPreventSpam;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class PreventSpamServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Load views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'prevent-spam');

        // Publish files
        $this->publishes([
            __DIR__.'/../config/honeypot.php' => config_path('honeypot.php'),
        ], 'config');
        $this->mergeConfigFrom(
            __DIR__.'/../config/honeypot.php',
            'honeypot'
        );

        // Publish views
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/prevent-spam'),
        ], 'views');

        // ✅ Register Blade component tag
        Blade::componentNamespace(
            'Tuantq\\LaravelPreventSpam\\View\\Components',
            'prevent-spam'
        );

        Blade::component('honeypot', Honeypot::class);
    }

    public function register()
    {
        $this->app->singleton(Honeypot::class, function ($app) {
            return new Honeypot(
                $app->make(Request::class),
                $app->make('config')->get('honeypot')
            );
        });
    }
}
