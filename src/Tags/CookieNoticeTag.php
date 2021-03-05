<?php

namespace DoubleThreeDigital\CookieNotice\Tags;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;
use Statamic\Facades\GlobalSet;
use Statamic\Facades\Site;
use Statamic\Tags\Tags;

class CookieNoticeTag extends Tags
{
    protected static $handle = 'cookie_notice';

    public function index()
    {
        $viewData = array_merge($this->gatherData(), [
            'hasConsented' => $this->hasConsented(),
            'groups' => collect(Config::get('cookie-notice.groups'))
                ->map(function ($value, $key) {
                    return array_merge($value, [
                        'name' => $key,
                        'slug' => 'group_'.str_slug($key),
                        'consented' => $this->hasConsented(str_slug($key)),
                    ]);
                })
                ->values()
                ->toArray(),
        ]);

        return view(
            'cookie-notice::notice',
            $viewData
        );
    }

    public function hasConsented(string $groupName = null)
    {
        $group = ! is_null($groupName) ? $groupName : str_slug($this->params->get('group'));

        $consent = json_decode(
            Cookie::get(Config::get('cookie-notice.cookie_name'))
        );

        if (! $group) {
            return is_array($consent);
        }

        return is_array($consent) ? in_array($group, $consent) : false;
    }

    protected function gatherData(): array
    {
        $data = [
            'csrf_field' => csrf_field(),
            'csrf_token' => csrf_token(),

            'current_date' => $now = now(),
            'now' => $now,
            'today' => $now,

            'site' => Site::current(),
            'sites' => Site::all()->values(),
        ];

        foreach (GlobalSet::all() as $global) {
            if (! $global->existsIn(Site::current()->handle())) {
                continue;
            }

            $global = $global->in(Site::current()->handle());

            $data[$global->handle()] = $global->toAugmentedArray();
        }

        return $data;
    }
}
