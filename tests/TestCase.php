<?php

namespace DuncanMcClean\CookieNotice\Tests;

use DuncanMcClean\CookieNotice\ServiceProvider;
use Statamic\Testing\AddonTestCase;

abstract class TestCase extends AddonTestCase
{
    protected string $addonServiceProvider = ServiceProvider::class;
}
