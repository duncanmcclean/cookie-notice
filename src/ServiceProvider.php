<?php

namespace DuncanMcClean\CookieNotice;

use Illuminate\Support\Facades\File;
use Statamic\Facades\CP\Nav;
use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    protected $publishables = [
        __DIR__.'/../dist' => '',
    ];

    protected $routes = [
        'cp' => __DIR__.'/../routes/cp.php',
    ];

    protected $tags = [
        Tags\CookieNoticeTag::class,
    ];

    public function bootAddon()
    {
        Nav::extend(function ($nav) {
            $nav->create('Cookie Notice')
                ->section('Tools')
                ->route('cookie-notice.scripts.edit')
                ->icon(File::get(__DIR__.'/../resources/svg/cookie.svg'))
                ->children([
                    'Scripts' => cp_route('cookie-notice.scripts.edit'),
                ]);
        });
    }
}
