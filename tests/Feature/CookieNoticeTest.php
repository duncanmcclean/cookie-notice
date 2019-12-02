<?php

namespace Damcclean\CookieNotice\Tests;

use Damcclean\CookieNotice\Tags\CookieNoticeTag;
use Statamic\Facades\Config;

class CookieNoticeTest extends TestCase
{
    /** @test */
    public function user_can_see_cookie_notice()
    {
        $notice = (new CookieNoticeTag())->index();

        $this
            ->assertStringContainsString('cookie-notice', $notice);
    }

    /** @test */
    public function are_default_styles_being_used_if_disabled()
    {
        Config::set('cookie-notice.disable_styles', true);

        $notice = (new CookieNoticeTag())->index();

        $this
            ->assertStringContainsString('cookie-notice', $notice);

        $this
            ->assertStringNotContainsString('<style>', $notice);

        $this
            ->assertStringNotContainsString('.cookie-notice .cookie-notice-message', $notice);
    }
}
