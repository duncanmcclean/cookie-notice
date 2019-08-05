<?php

namespace Statamic\Addons\CookieNotice;

use Statamic\Extend\HasParameters;
use Statamic\Extend\Tags;

class CookieNoticeTags extends Tags
{
    use HasParameters;

    public function __construct()
    {
        $this->styles = $this->getConfig('styles', false);
        $this->text = $this->getConfig('text', 'This website uses cookies to give you the best user experience.');
    }

    public function index()
    {
        return $this->view('notice', [
            'text' => $this->text,
            'styles' => $this->styles
        ]);
    }
}
