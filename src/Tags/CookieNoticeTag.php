<?php

namespace DoubleThreeDigital\CookieNotice\Tags;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;
use Statamic\Tags\Tags;

class CookieNoticeTag extends Tags
{
    protected static $handle = 'cookie_notice';

    public function index()
    {
        return view('cookie-notice::notice', [
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
}
