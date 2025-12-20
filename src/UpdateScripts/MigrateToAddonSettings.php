<?php

namespace DuncanMcClean\CookieNotice\UpdateScripts;

use Illuminate\Support\Facades\File;
use Statamic\Facades\Addon;
use Statamic\Facades\Preference;
use Statamic\Facades\Role;
use Statamic\Facades\Site;
use Statamic\Facades\User;
use Statamic\Facades\YAML;
use Statamic\UpdateScripts\UpdateScript;

class MigrateToAddonSettings extends UpdateScript
{
    public function shouldUpdate($newVersion, $oldVersion)
    {
        return $this->isUpdatingTo('10.0.0');
    }

    public function update()
    {
        if (! File::exists($path = base_path('content/cookie-notice.yaml'))) {
            return;
        }

        $data = YAML::file($path)->parse();

        Addon::get('duncanmcclean/cookie-notice')
            ->settings()
            ->set([Site::default()->handle() => $data])
            ->save();

        File::delete($path);
    }
}
