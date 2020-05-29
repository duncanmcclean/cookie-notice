<?php

namespace DoubleThreeDigital\CookieNotice\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class CookieNoticeActionController extends Controller
{
    public function store(Request $request)
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

        Cookie::queue(
            Config::get('cookie-notice.cookie_name'),
            json_encode($accepted),
            43200 // 30 days
        );

        // Session::put(Config::get('cookie-notice.cookie_name'), json_encode($accepted));

        return back();
    }

    public function update()
    {
        //
    }

    protected function getValidationRules()
    {
        // $rules = [];
        // $groups = Config::get('cookie-notice.groups');

        // foreach ($groups as $groupName => $group) {
        //     $name = 'group_'.str_slug($groupName);

        //     if ($group['required'] === true) {
        //         $rules[$name] = ['required', 'checked'];
        //     } else {
        //         $rules[$name] = ['nullable'];
        //     }
        // }

        // return $rules;

        return [];
    }
}