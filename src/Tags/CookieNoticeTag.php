<?php

namespace DuncanMcClean\CookieNotice\Tags;

use DuncanMcClean\CookieNotice\Scripts\Scripts;
use Illuminate\Support\Facades\Vite;
use Statamic\Tags\Tags;

class CookieNoticeTag extends Tags
{
    protected static $handle = 'cookie_notice';

    public function index()
    {
        return $this->widget();
    }

    public function widget()
    {
        if ($this->context->get('live_preview')) {
            return;
        }

        return view(config('cookie-notice.widget_view', 'cookie-notice::widget'), [
            'config' => [
                'cookie_name' => config('cookie-notice.cookie_name', 'COOKIE_NOTICE'),
                'cookie_expiry' => config('cookie-notice.cookie_expiry', 14),
                'consent_groups' => config('cookie-notice.consent_groups'),
                'revision' => Scripts::revision(),
                'session' => [
                    'domain' => config('session.domain') ?? request()->getHost(),
                    'secure' => config('session.secure'),
                    'same_site' => config('session.same_site'),
                ],
            ],
            'consent_groups' => config('cookie-notice.consent_groups'),
            'inline_css' => Vite::useBuildDirectory('vendor/cookie-notice/build')
                ->useHotFile(__DIR__.'/../../vite.hot')
                ->content('resources/css/cookie-notice.css'),
        ]);
    }

    public function scripts()
    {
        if ($this->context->get('live_preview')) {
            return;
        }

        $js = Vite::useBuildDirectory('vendor/cookie-notice/build')
            ->useHotFile(__DIR__.'/../../vite.hot')
            ->content('resources/js/cookie-notice.js');

        return view('cookie-notice::scripts', [
            'inline_js' => $js,
            'scripts' => collect(Scripts::scripts())
                ->filter(fn ($value, $key) => in_array($key, collect(config('cookie-notice.consent_groups'))->pluck('handle')->all()))
                ->flatMap(function (array $scripts, string $consentGroup) {
                    return collect($scripts)->map(function (array $script) use ($consentGroup): array {
                        return [
                            ...$script,
                            'group' => $consentGroup,
                            'consent_types' => $script['consent_types'] ?? [
                                'ad_storage', 'ad_user_data', 'ad_personalization', 'analytics_storage',
                            ],
                        ];
                    })->all();
                }),
        ]);
    }
}
