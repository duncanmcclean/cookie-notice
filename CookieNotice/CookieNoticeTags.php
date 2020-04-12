<?php

namespace Statamic\Addons\CookieNotice;

use Statamic\Extend\Tags;

class CookieNoticeTags extends Tags
{
    public function index()
    {
        return $this->view('notice', [
            'domainName' => request()->getHost(),
            'cookieName' => $this->getConfig('cookie-name', 'cookie_notice'),
            'noticeText' => $this->getConfig('text', 'Your experience on this site will be improved by allowing cookies.'),
            'buttonLabel' => $this->getConfig('button_label', 'Allow cookies'),
            'noticeStyles' => $this->getConfig('disable_styles', false),
            'noticeLocation' => $this->getConfig('location', 'bottom'),
        ]);
    }
}
