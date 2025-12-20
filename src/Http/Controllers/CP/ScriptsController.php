<?php

namespace DuncanMcClean\CookieNotice\Http\Controllers\CP;

use DuncanMcClean\CookieNotice\Scripts;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Inertia\Inertia;
use Statamic\Facades\Addon;
use Statamic\Facades\Site;
use Statamic\Facades\User;
use Statamic\Http\Controllers\CP\CpController;

class ScriptsController extends CpController
{
    public function edit(Request $request)
    {
        $site = $this->determineSite($request);

        $settings = Addon::get('duncanmcclean/cookie-notice')->settings()->raw();
        $values = Arr::get($settings, $site, []);

        $blueprint = Scripts\Blueprint::blueprint();
        $fields = $blueprint->fields()->addValues($values)->preProcess();

        $viewData = [
            'icon' => File::get(__DIR__.'/../../../../resources/svg/cookie.svg'),
            'blueprint' => $blueprint->toPublishArray(),
            'initialSite' => $site,
            'initialValues' => $fields->values(),
            'initialMeta' => $fields->meta(),
            'initialLocalizations' => Site::all()
                ->filter(fn ($site) => User::current()->can('view', $site))
                ->map(function ($localization) use ($site): array {
                    return [
                        'handle' => $localization->handle(),
                        'name' => $localization->name,
                        'active' => $localization->locale() === $site,
                        'url' => cp_route('cookie-notice.scripts.edit', ['site' => $localization->handle()]),
                    ];
                })
                ->when(! Site::hasMultiple() || ! config('cookie-notice.configure_scripts_per_site', false), function ($localizations) {
                    return $localizations->filter(fn ($localization) => $localization['handle'] === Site::default()->handle());
                })
                ->values()
                ->all(),
            'action' => cp_route('cookie-notice.scripts.edit'),
        ];

        if ($request->wantsJson()) {
            return $viewData;
        }

        return Inertia::render('cookie-notice::Scripts/Edit', $viewData);
    }

    public function update(Request $request)
    {
        $blueprint = Scripts\Blueprint::blueprint();
        $fields = $blueprint->fields()->addValues($request->all());

        $fields->validate();

        $values = $fields->process()->values()->all();

        $values = $this->processScriptValues($values);

        Addon::get('duncanmcclean/cookie-notice')
            ->settings()
            ->set($this->determineSite($request), $values)
            ->save();
    }

    private function processScriptValues(array $values): array
    {
        return collect($values)->map(function ($scripts, string $group) {
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
    }

    private function determineSite(Request $request): string
    {
        if (Site::hasMultiple() && config('cookie-notice.configure_scripts_per_site', false)) {
            return $request->site ?? Site::selected()->handle();
        }

        return Site::default()->handle();
    }
}
