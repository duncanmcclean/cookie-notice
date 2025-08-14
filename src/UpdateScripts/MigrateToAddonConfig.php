<?php

namespace DuncanMcClean\CookieNotice\UpdateScripts;

use Illuminate\Support\Facades\File;
use Statamic\Facades\Addon;
use Statamic\Facades\Preference;
use Statamic\Facades\Role;
use Statamic\Facades\User;
use Statamic\Facades\YAML;
use Statamic\UpdateScripts\UpdateScript;

class MigrateToAddonConfig extends UpdateScript
{
    public function shouldUpdate($newVersion, $oldVersion)
    {
        return $this->isUpdatingTo('10.0.0');
    }

    public function update()
    {
        $this
            ->migrateScripts()
            ->updatePermissions()
            ->updateNavPreferences();
    }

    private function migrateScripts(): self
    {
        if (! File::exists($path = base_path('content/cookie-notice.yaml'))) {
            return $this;
        }

        $data = YAML::file($path)->parse();

        Addon::get('duncanmcclean/cookie-notice')
            ->settings()
            ->set($data)
            ->save();

        File::delete($path);

        return $this;
    }

    private function updatePermissions(): self
    {
        Role::all()
            ->filter(fn ($role) => $role->hasPermission('manage scripts'))
            ->each(function ($role) {
                $role
                    ->removePermission('manage scripts')
                    ->addPermission('edit cookie-notice settings')
                    ->save();
            });

        return $this;
    }

    private function updateNavPreferences(): self
    {
        $defaultPreferences = Preference::default();

        if ($defaultPreferences->hasPreference('nav')) {
            $defaultPreferences
                ->setPreference('nav', $this->replaceKeyInNavPreferences($defaultPreferences->getPreference('nav') ?? []))
                ->save();
        }

        Role::all()
            ->filter(fn ($role) => $role->hasPreference('nav'))
            ->each(fn ($role) => $role->setPreference('nav', $this->replaceKeyInNavPreferences($role->getPreference('nav')))->save());

        User::query()
            ->whereNotNull('preferences')
            ->chunk(100, function ($users) {
                $users
                    ->filter(fn ($user) => $user->hasPreference('nav'))
                    ->each(fn ($user) => $user->setPreference('nav', $this->replaceKeyInNavPreferences($user->getPreference('nav')))->save());
            });

        return $this;
    }

    private function replaceKeyInNavPreferences(array $value): array
    {
        $json = json_encode($value);

        if (! str_contains($json, 'tools::cookie_notice::scripts')) {
            return $value;
        }

        $json = str_replace('tools::cookie_notice::scripts', 'tools::cookie_notice', $json);

        return json_decode($json, true);
    }
}
