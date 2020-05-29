<?php

namespace DoubleThreeDigital\CookieNotice\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;

class CookieNoticeActionController extends Controller
{
    public function update(Request $request)
    {
        $request->validate($this->getValidationRules());

        $accepted = collect($request->all())
            ->filter(function ($value, $key) {
                return str_contains($key, 'group_');
            })
            ->mapWithKeys(function ($value, $key) {
                return [str_replace('group_', '', $key) => $value];
            })
            ->keys()
            ->toArray();

        Cookie::queue(Cookie::forget(Config::get('cookie-notice.cookie_name')));

        // dd(Cookie::get(Config::get('cookie-notice.cookie_name')));
            
        Cookie::queue(
            Config::get('cookie-notice.cookie_name'),
            json_encode($accepted),
            43200 // 30 days
        );

        return back();
    }

    protected function getValidationRules()
    {
        $rules = [];
        $groups = Config::get('cookie-notice.groups');

        foreach ($groups as $groupName => $group) {
            $name = 'group_'.str_slug($groupName);

            if ($group['required'] === true) {
                $rules[$name] = ['required', 'accepted'];
            } else {
                $rules[$name] = ['nullable'];
            }
        }

        return $rules;
    }
}