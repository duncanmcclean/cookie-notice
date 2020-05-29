<?php

namespace DoubleThreeDigital\CookieNotice;

use DoubleThreeDigital\CookieNotice\Tags\CookieNoticeTag;
use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    protected $tags = [
        CookieNoticeTag::class,
    ];

    protected $routes = [
        'actions' => __DIR__.'/../routes/actions.php',
    ];

    public function boot()
    {
        parent::boot();

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'cookie-notice');
        $this->publishes([__DIR__.'/../config/cookie-notice.php' => config_path('cookie-notice.php')], 'cookie-notice-config');
    }

    public function register()
    {
        if (! $this->app->configurationIsCached()) {
            $this->mergeConfigFrom(__DIR__.'/../config/cookie-notice.php', 'cookie-notice');
        }
    }
}
