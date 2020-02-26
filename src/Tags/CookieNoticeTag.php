<?php

namespace DoubleThreeDigital\CookieNotice\Tags;

use Statamic\Tags\Tags;

class CookieNoticeTag extends Tags
{
    protected static $handle = 'cookie_notice';

    public function index()
    {
        return view('cookie-notice::notice', [
            'domainName' => request()->getHost(),
            'cookieName' => config('cookie-notice.cookie_name', 'cookie_notice'),
            'noticeText' => config('cookie-notice.text', 'Your experience on this site will be improved by allowing cookies.'),
            'noticeStyles' => config('cookie-notice.disable_styles', false),
            'noticeLocation' => config('cookie-notice.location', 'bottom')
        ]);
    }
}
