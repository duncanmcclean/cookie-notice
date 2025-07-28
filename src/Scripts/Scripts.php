<?php

namespace DuncanMcClean\CookieNotice\Scripts;

use Illuminate\Support\Arr;
use Statamic\Facades\Addon;

class Scripts
{
    public static function data(): array
    {
        return Addon::get('duncanmcclean/cookie-notice')
            ->settings()
            ->raw();
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
