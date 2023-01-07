<?php

namespace DoubleThreeDigital\CookieNotice\Tags;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Statamic\Facades\Addon;
use Statamic\Facades\GlobalSet;
use Statamic\Facades\Site;
use Statamic\Tags\Tags;

class CookieNoticeTag extends Tags
{
    public static $alreadyRenderedScripts = false;

    protected static $handle = 'cookie_notice';

    public function index()
    {
        return view('cookie-notice::notice', $this->viewData());
    }

    public function scripts()
    {
        static::$alreadyRenderedScripts = true;

        return view('cookie-notice::scripts', $this->viewData());
    }

    protected function groups(): array
    {
        return collect(Config::get('cookie-notice.groups'))
            ->map(function ($value, $key) {
                return array_merge($value, [
                    'name' => $key,
                    'slug' => 'group_'.str_slug($key),
                ]);
            })
            ->values()
            ->toArray();
    }

    protected function viewData(): array
    {
        $array = [
            // Some Statamic-y variables
            'csrf_field' => csrf_field(),
            'csrf_token' => csrf_token(),

            'current_date' => $now = now(),
            'now' => $now,
            'today' => $now,

            'site' => Site::current(),
            'sites' => Site::all()->values(),

            // Cookie Notice variables
            'domain'        => config('session.domain') ?? request()->getHost(),
            'cookie_name'   => config('cookie-notice.cookie_name'),
            'groups'        => $this->groups(),
            'already_rendered_scripts' => static::$alreadyRenderedScripts,
        ];

        // Push data from Globals into the view
        foreach (GlobalSet::all() as $global) {
            if (! $global->existsIn(Site::current()->handle())) {
                continue;
            }

            $global = $global->in(Site::current()->handle());

            $array[$global->handle()] = $global->toAugmentedArray();
        }

        // Get the CSS from the site's vendor/cookie-notice directory (as otherwise, some ad-blockers will block the styles)
        $cookieNoticeVersion = Addon::get('doublethreedigital/cookie-notice')->version();

        $array['inline_css'] = Cache::rememberForever("CookieNotice:{$cookieNoticeVersion}:InlineCss", function () {
            return File::get(public_path('vendor/cookie-notice/css/cookie-notice.css'));
        });

        return $array;
    }
}
