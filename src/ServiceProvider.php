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

    protected $publishables = [
        __DIR__.'/../dist' => 'vendor/cookie-notice',
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
            __DIR__.'/../config/cookie-notice.php' => config_path('cookie-notice.php'),
        ], 'cookie-notice-config');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/cookie-notice'),
        ], 'cookie-notice-views');

        $this->mergeConfigFrom(__DIR__.'/../config/cookie-notice.php', 'cookie-notice');
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'cookie-notice');

        return $this;
    }

    protected function bootPublishAfterInstall()
    {
        if (! $this->publishAfterInstall) {
            return $this;
        }

        // Temp: Adds $this->publishables to the list of publishable assets.
        if (empty($this->scripts) && empty($this->stylesheets) && empty($this->vite) && empty($this->publishables)) {
            return $this;
        }

        Statamic::afterInstalled(function ($command) {
            $command->call('vendor:publish', [
                '--tag' => $this->getAddon()->slug(),
                '--force' => true,
            ]);
        });

        return $this;
    }
}
