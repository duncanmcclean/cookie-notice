<?php

namespace DoubleThreeDigital\CookieNotice;

use DoubleThreeDigital\CookieNotice\Tags\CookieNoticeTag;
use Statamic\Providers\AddonServiceProvider;
use Statamic\Statamic;

class ServiceProvider extends AddonServiceProvider
{
    protected $config = false;
    protected $translations = false;

    protected $tags = [
        CookieNoticeTag::class,
    ];

    public function boot()
    {
        parent::boot();

        Statamic::booted(function () {
            $this
                ->bootVendorAssets();
        });
    }

    protected function bootVendorAssets()
    {
        $this->publishes([
            __DIR__.'/../resources/dist' => public_path('vendor/cookie-notice'),
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
