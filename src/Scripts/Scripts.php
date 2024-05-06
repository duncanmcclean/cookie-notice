<?php

namespace DuncanMcClean\CookieNotice\Scripts;

use Illuminate\Support\Facades\File;
use Statamic\Facades\YAML;

class Scripts
{
    public static function get(): array
    {
        if (! File::exists(static::path())) {
            return [];
        }

        return YAML::file(static::path())->parse();
    }

    public static function save(array $data): void
    {
        $yaml = YAML::dump($data);

        File::ensureDirectoryExists(dirname(static::path()));
        File::put(static::path(), $yaml);
    }

    private static function path(): string
    {
        return storage_path('statamic/addons/cookie-notice/scripts.yaml');
    }
}
