<?php

namespace DoubleThreeDigital\CookieNotice\Tests;

use DoubleThreeDigital\CookieNotice\Tags\CookieNoticeTag;
use Statamic\Facades\Antlers;

class CookieNoticeTest extends TestCase
{
    public $tag;

    public function setUp(): void
    {
        parent::setUp();

        $this->tag = (new CookieNoticeTag())
            ->setParser(Antlers::parser())
            ->setContext([]);
    }

    /** @test */
    public function cookie_notice_tag_is_registered()
    {
        $this->assertTrue(isset($this->app['statamic.tags']['cookie_notice']));
    }

    /** @test */
    public function cookie_notice_can_be_seen()
    {
        $usage = $this->tag->index();
        $html = $usage->render();

        $this->assertSame($usage->getName(), 'cookie-notice::notice');
        $this->assertArrayHasKey('domainName', $usage->getData());

        $this->assertIsString($html);
        $this->assertStringContainsString('<div class="cookie-notice">', $html);
        $this->assertStringContainsString('window.cookieNotice', $html);
    }

    /** @test */
    public function cookie_notice_is_positioned_at_the_bottom()
    {
        $this->app['config']->set('cookie-notice.disable_styles', false);
        $this->app['config']->set('cookie-notice.location', 'bottom');

        $usage = $this->tag->index();
        $html = $usage->render();

        $this->assertSame($usage->getName(), 'cookie-notice::notice');

        $this->assertIsString($html);
        $this->assertStringContainsString('bottom: 0px;', $html);
    }

    /** @test */
    public function cookie_notice_is_positioned_at_the_top()
    {
        $this->app['config']->set('cookie-notice.disable_styles', false);
        $this->app['config']->set('cookie-notice.location', 'top');

        $usage = $this->tag->index();
        $html = $usage->render();

        $this->assertSame($usage->getName(), 'cookie-notice::notice');

        $this->assertIsString($html);
        $this->assertStringContainsString('top: 0px;', $html);
    }

    /** @test */
    public function cookie_notice_styles_are_disabled()
    {
        $this->app['config']->set('cookie-notice.disable_styles', true);

        $usage = $this->tag->index();
        $html = $usage->render();

        $this->assertSame($usage->getName(), 'cookie-notice::notice');

        $this->assertIsString($html);
        $this->assertStringNotContainsString('<style>', $html);
    }

    /** @test */
    public function cookie_notice_text_is_shown()
    {
        $this->app['config']->set('cookie-notice.text', 'Custom cookie text');

        $usage = $this->tag->index();
        $html = $usage->render();

        $this->assertSame($usage->getName(), 'cookie-notice::notice');

        $this->assertIsString($html);
        $this->assertStringContainsString('Custom cookie text', $html);
    }

    /** @test */
    public function cookie_notice_cookie_name_is_used()
    {
        $this->app['config']->set('cookie-notice.cookie_name', 'CUSTOM_COOKIE_NAME');

        $usage = $this->tag->index();
        $html = $usage->render();

        $this->assertSame($usage->getName(), 'cookie-notice::notice');

        $this->assertIsString($html);
        $this->assertStringContainsString('CUSTOM_COOKIE_NAME', $html);
    }
}
