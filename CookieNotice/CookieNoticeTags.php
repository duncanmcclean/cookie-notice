<?php

namespace Statamic\Addons\CookieNotice;

use Statamic\Extend\Tags;

class CookieNoticeTags extends Tags
{
    public function __construct()
    {
        $this->styles = $this->getConfig('styles', false);
        $this->text = $this->getConfig('text', 'In order to use this website, you must accept cookies.');
    }

    public function index()
    {
        return $this->view('notice', [
            'text' => $this->text,
            'styles' => $this->styles
        ]);
    }
}
