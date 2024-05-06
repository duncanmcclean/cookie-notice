<?php

namespace DuncanMcClean\CookieNotice\Tests;

use DuncanMcClean\CookieNotice\ServiceProvider;
use Statamic\Testing\AddonTestCase;

abstract class TestCase extends AddonTestCase
{
    protected string $addonServiceProvider = ServiceProvider::class;

    protected function setUp(): void
    {
        parent::setUp();

        $this->app['files']->delete(base_path('content/cookie-notice.yaml'));
    }
}
