<?php

namespace DuncanMcClean\CookieNotice\UpdateScripts;

use Illuminate\Support\Facades\File;
use Statamic\UpdateScripts\UpdateScript;

class BreakingChangesWarning extends UpdateScript
{
    public function shouldUpdate($newVersion, $oldVersion)
    {
        return $this->isUpdatingTo('5.0.0');
    }

    public function update()
    {
        // Have assets been published? If so, re-publish.
        if (File::exists(public_path('vendor/cookie-notice'))) {
            dump('[Cookie Notice] BREAKING CHANGE: Please re-publish the Cookie Notice assets. See v5.0.0 changelog. https://github.com/duncanmcclean/cookie-notice/releases/tag/v5.0.0');
        }

        // Has antlers view been published? If so, show a warning..
        if (File::exists(resource_path('views/vendor/cookie-notice'))) {
            dump('[Cookie Notice] BREAKING CHANGE: Please re-publish the Cookie Notice view. See v5.0.0 changelog. https://github.com/duncanmcclean/cookie-notice/releases/tag/v5.0.0');
        }

        // Warn the user about user consent changes
        dump('[Cookie Notice] BREAKING CHANGE: Checking for user consent has changed, please see the v5.0.0 changelog. https://github.com/duncanmcclean/cookie-notice/releases/tag/v5.0.0');
    }
}
