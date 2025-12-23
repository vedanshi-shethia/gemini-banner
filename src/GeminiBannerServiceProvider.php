<?php

namespace Vedanshi\GeminiBanner;

use Illuminate\Support\ServiceProvider;
use Vedanshi\GeminiBanner\Services\GeminiBannerService;

class GeminiBannerServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/gemini-banner.php',
            'gemini-banner'
        );

        $this->app->singleton(GeminiBannerService::class);
    }

    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/gemini-banner.php' => config_path('gemini-banner.php'),
        ], ['config', 'gemini-banner']);
    }
}
