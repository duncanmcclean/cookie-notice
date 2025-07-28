<?php

namespace DuncanMcClean\CookieNotice\Listeners;

use Statamic\Events\AddonSettingsSaving;

class UpdateSettingsBeforeSaving
{
    public function handle(AddonSettingsSaving $event): void
    {
        $values = collect($event->settings->raw())->map(function ($scripts, string $group) {
            // We only want to manipulate the consent group fields.
            if (! collect(config('cookie-notice.consent_groups'))->keyBy('handle')->has($group)) {
                return $scripts;
            }

            return collect($scripts)->map(function (array $script) {
                // When you deselect all consent types, we need to save an empty array to
                // prevent it falling back to the field's default value (all consent types).
                if ($script['script_type'] === 'google-tag-manager') {
                    if (! isset($script['gtm_consent_types'])) {
                        $script['gtm_consent_types'] = [];
                    }

                    return $script;
                }

                return $script;
            })->all();
        })->all();

        $event->settings->set($values);
    }
}
