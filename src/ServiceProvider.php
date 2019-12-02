<?php

namespace Damcclean\CookieNotice;

use Damcclean\CookieNotice\Tags\CookieNoticeTag;
use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    protected $tags = [
        CookieNoticeTag::class
    ];

    public function boot()
    {
        parent::boot();

        $this
            ->loadViewsFrom(__DIR__.'/../resources/views', 'cookie-notice');

        $this->publishes([
            __DIR__.'/../config/cookie-notice.php' => config_path('cookie-notice.php')
        ], 'config');
    }
}
