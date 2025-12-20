<?php

namespace DuncanMcClean\CookieNotice\Scripts;

use Illuminate\Support\Arr;
use Statamic\Facades\Addon;
use Statamic\Facades\Site;

class Scripts
{
    public static function data(): array
    {
        $site = Site::default()->handle();

        if (Site::hasMultiple() && config('cookie-notice.configure_scripts_per_site', false)) {
            $site = Site::current()->handle();
        }

        ray($site, Site::current(), Site::hasMultiple(), config('cookie-notice.configure_scripts_per_site', false));

        $settings = Addon::get('duncanmcclean/cookie-notice')->settings()->raw();

        return Arr::get($settings, $site, []);
    }

    public static function revision(): int
    {
        return Arr::get(static::data(), 'revision', 0);
    }

    public static function scripts(): array
    {
        return collect(config('cookie-notice.consent_groups'))
            ->mapWithKeys(fn (array $consentGroup) => [$consentGroup['handle'] => Arr::get(static::data(), $consentGroup['handle'], [])])
            ->filter()
            ->all();
    }
}
