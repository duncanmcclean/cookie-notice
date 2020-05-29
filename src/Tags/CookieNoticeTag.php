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
            'config' => Config::get('cookie-notice'),
        ]);
    }

    public function hasConsented()
    {
        $group = str_slug($this->getParam('group'));

        $givenConsent = json_decode(Cookie::get(Config::get('cookie-notice.cookie_name')));
        
        return is_array($givenConsent) ? in_array($group, $givenConsent) : false;
    }
}
