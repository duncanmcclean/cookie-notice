<?php

namespace Statamic\Addons\CookieNotice;

use Statamic\Extend\Tags;

class CookieNoticeTags extends Tags
{
    public function __construct()
    {
        define('COOKIE_NOTICE_VERSION', 1.5);

        $this->styles = $this->getConfig('styles', false);
        $this->text = $this->getConfig('text', 'This website uses cookies to give you the best user experience.');
        $this->location = $this->getConfig('location', 'bottom');
        $this->dontAccept = $this->getConfig('dont-accept', true);
        $this->cookieName = $this->getConfig('cookie-name', 'cookieNotice'.COOKIE_NOTICE_VERSION);
    }

    public function index()
    {
        return $this->view('notice', [
            'text' => $this->text,
            'styles' => $this->styles,
            'location' => $this->location,
            'dontAccept' => $this->dontAccept,
            'cookieName' => $this->cookieName,
        ]);
    }
}
