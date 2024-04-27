<?php

namespace DuncanMcClean\CookieNotice;

use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    protected $tags = [
        Tags\CookieNoticeTag::class,
    ];

    protected $publishables = [
        __DIR__.'/../dist' => '',
    ];
}
