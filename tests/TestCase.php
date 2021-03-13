<?php

namespace DoubleThreeDigital\CookieNotice\Tests;

use DoubleThreeDigital\CookieNotice\ServiceProvider;
use Statamic\Extend\Manifest;
use Statamic\Providers\StatamicServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Statamic\Statamic;

abstract class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            StatamicServiceProvider::class,
            ServiceProvider::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Statamic' => Statamic::class
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app->make(Manifest::class)->manifest = [
            'doublethreedigital/cookie-notice' => [
                'id' => 'doublethreedigital/cookie-notice',
                'namespace' => 'DoubleThreeDigital\\CookieNotice\\',
            ],
        ];

        Statamic::pushActionRoutes(function() {
            return require_once realpath(__DIR__.'/../routes/actions.php');
        });
    }

    protected function resolveApplicationConfiguration($app)
    {
        parent::resolveApplicationConfiguration($app);

        $configs = [
            'assets', 'cp', 'forms', 'static_caching',
            'sites', 'stache', 'system', 'users'
        ];

        foreach ($configs as $config) {
            $app['config']->set("statamic.$config", require(__DIR__."/../vendor/statamic/cms/config/{$config}.php"));
        }

        $app['config']->set('statamic.users.repository', 'file');
    }

    public function tearDown(): void
    {
        // Destroy the $app
        if ($this->app) {
            $this->callBeforeApplicationDestroyedCallbacks();

            $this->app = null;
        }

       // Call parent teardown
       parent::tearDown();
    }
}
