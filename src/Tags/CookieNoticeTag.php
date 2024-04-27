<?php

namespace DuncanMcClean\CookieNotice\Tags;

use Illuminate\Support\Facades\Vite;
use Statamic\Facades\GlobalSet;
use Statamic\Facades\Site;
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

        return view(config('cookie-notice.widget_view', 'cookie-notice::widget'), $this->viewData());
    }

    public function scripts()
    {
        if ($this->context->get('live_preview')) {
            return;
        }

        $js = Vite::useBuildDirectory('vendor/cookie-notice/build')
            ->useHotFile(__DIR__ . '/../../vite.hot')
            ->content('resources/js/cookie-notice.js');

        // TODO: refactor to get from custom cp page
        return view('cookie-notice::scripts', array_merge($this->viewData(), ['inline_js' => $js]));
    }

    protected function viewData(): array
    {
        return array_merge([
            'config' => [
                'cookie_name' => config('cookie-notice.cookie_name', 'COOKIE_NOTICE'),
                'cookie_expiry' => config('cookie-notice.cookie_expiry', 14),
                'consent_groups' => config('cookie-notice.consent_groups'),
                'session' => [
                    'domain' => config('session.domain') ?? request()->getHost(),
                    'secure' => config('session.secure'),
                    'same_site' => config('session.same_site'),
                ],
            ],
            'consent_groups' => config('cookie-notice.consent_groups'),
            'inline_css' => Vite::useBuildDirectory('vendor/cookie-notice/build')
                ->useHotFile(__DIR__ . '/../../vite.hot')
                ->content('resources/css/cookie-notice.css'),
        ], $this->getGlobalsData());
    }

    protected function getGlobalsData(): array
    {
        $globalsData = [];

        foreach (GlobalSet::all() as $global) {
            if (! $global->existsIn(Site::current()->handle())) {
                continue;
            }

            $global = $global->in(Site::current()->handle());

            $globalsData[$global->handle()] = $global->toAugmentedArray();
        }

        return $globalsData;
    }
}
