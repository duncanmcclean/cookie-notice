<?php

namespace DuncanMcClean\CookieNotice\Scripts;

use DuncanMcClean\CookieNotice\Events\ScriptsSaved;
use Illuminate\Support\Facades\File;
use Statamic\Facades\YAML;

class Scripts
{
    public static function data(): array
    {
        if (! File::exists(static::path())) {
            return [];
        }

        return YAML::file(static::path())->parse();
    }

    public static function revision(): int
    {
        return static::data()['revision'] ?? 1;
    }

    public static function scripts(): array
    {
        return collect(config('cookie-notice.consent_groups'))
            ->mapWithKeys(fn (array $consentGroup) => [$consentGroup['handle'] => static::data()[$consentGroup['handle']] ?? []])
            ->filter()
            ->all();
    }

    public static function save(array $data): void
    {
        $yaml = YAML::dump($data);

        File::ensureDirectoryExists(dirname(static::path()));
        File::put(static::path(), $yaml);

        ScriptsSaved::dispatch();
    }

    private static function path(): string
    {
        return base_path('content/cookie-notice.yaml');
    }
}
