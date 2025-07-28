<?php

namespace DuncanMcClean\CookieNotice;

use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    protected $publishables = [
        __DIR__.'/../dist' => '',
    ];

    public function bootAddon(): void
    {
        $this->registerSettingsBlueprint(Scripts\Blueprint::blueprint()->contents());
    }
}
