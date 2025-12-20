<?php

namespace DuncanMcClean\CookieNotice;

use Illuminate\Support\Facades\File;
use Statamic\Facades\CP\Nav;
use Statamic\Facades\Permission;
use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    protected $publishables = [
        __DIR__.'/../dist' => '',
    ];

    protected $vite = [
        'hotFile' => __DIR__.'/../vite.hot',
        'publicDirectory' => 'dist',
        'input' => ['resources/js/cp.js'],
    ];

    public function bootAddon()
    {
        Permission::extend(function () {
            Permission::register('manage scripts')
                ->label(__('Manage Scripts'));
        });

        Nav::extend(function ($nav) {
            $nav->create(__('Cookie Notice'))
                ->section('Tools')
                ->route('cookie-notice.scripts.edit')
                ->icon(File::get(__DIR__.'/../resources/svg/cookie.svg'))
                ->can('manage scripts')
                ->children(['Scripts' => cp_route('cookie-notice.scripts.edit')]);
        });
    }
}
