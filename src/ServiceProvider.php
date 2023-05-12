<?php

namespace DuncanMcClean\CookieNotice;

use Statamic\Providers\AddonServiceProvider;
use Statamic\Statamic;

class ServiceProvider extends AddonServiceProvider
{
    protected $config = false;

    protected $translations = false;

    protected $tags = [
        Tags\CookieNoticeTag::class,
    ];

    protected $updateScripts = [
        UpdateScripts\BreakingChangesWarning::class,
    ];

    protected $vite = [
        'publicDirectory' => 'dist',
        'buildDirectory' => 'css',
        'input' => [
            'resources/css/cookie-notice.css',
        ],
    ];

    public function boot()
    {
        parent::boot();

        Statamic::booted(function () {
            $this->bootVendorAssets();
        });
    }

    protected function bootVendorAssets()
    {
        $this->publishes([
            __DIR__.'/../dist' => public_path('vendor/cookie-notice'),
        ], 'cookie-notice-assets');

        $this->publishes([
            __DIR__.'/../config/cookie-notice.php' => config_path('cookie-notice.php'),
        ], 'cookie-notice-config');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/cookie-notice'),
        ], 'cookie-notice-views');

        $this->mergeConfigFrom(__DIR__.'/../config/cookie-notice.php', 'cookie-notice');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'cookie-notice');

        return $this;
    }
}
