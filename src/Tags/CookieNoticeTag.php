<?php

namespace DoubleThreeDigital\CookieNotice\Tags;

use Illuminate\Support\Facades\Config;
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

        foreach (GlobalSet::all() as $global) {
            if (! $global->existsIn(Site::current()->handle())) {
                continue;
            }

            $global = $global->in(Site::current()->handle());

            $array[$global->handle()] = $global->toAugmentedArray();
        }

        return $array;
    }
}
