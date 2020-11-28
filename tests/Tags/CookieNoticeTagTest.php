<?php

namespace DoubleThreeDigital\CookieNotice\Tests\Tags;

use DoubleThreeDigital\CookieNotice\Tags\CookieNoticeTag;
use DoubleThreeDigital\CookieNotice\Tests\TestCase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cookie;
use Statamic\Facades\Antlers;

class CookieNoticeTagTest extends TestCase
{
    public $tag;

    public function setUp(): void
    {
        parent::setUp();

        $this->tag = (new CookieNoticeTag())
            ->setParser(Antlers::parser())
            ->setContext([]);

        Config::set('cookie-notice.groups', [
            'Necessary' => [
                'required' => true,
                'toggle_by_default' => true,
            ],
            'Statistics' => [
                'required' => false,
                'toggle_by_default' => false,
            ],
            'Marketing' => [
                'required' => false,
                'toggle_by_default' => false,
            ],
        ]);
    }

    /** @test */
    public function cookie_notice_tag_is_registered()
    {
        $this->assertTrue(isset($this->app['statamic.tags']['cookie_notice']));
    }

    /** @test */
    public function can_see_cookie_notice()
    {
        $this->tag->setParameters([]);
        
        $notice = $this->tag->index()->render();

        $this->assertIsString($notice);
        $this->assertStringContainsString('id="group_necessary"', $notice);
        $this->assertStringContainsString('id="group_statistics"', $notice);
        $this->assertStringContainsString('id="group_marketing"', $notice);
    }

    /** @test */
    public function can_get_if_consent_is_true()
    {
        // TODO: need to find a way to set/fake a cookie
        $this->markTestIncomplete();

        Cookie::queue(
            Config::get('cookie-notice.cookie_name'),
            json_encode(['Necessary', 'Statistics']),
            43200 // 30 days
        );

        $this->tag->setParameters(['group' => 'Statistics']);

        $notice = $this->tag->hasConsented();
        
        $this->assertIsBool($notice);
    }

    /** @test */
    public function can_get_if_consent_is_false()
    {
        // TODO: need to find a way to set/fake a cookie
        $this->markTestIncomplete();
    }

    /** @test */
    public function cookie_notice_settings_should_not_select_checkboxes_that_are_selected_by_default()
    {
        // TODO
        $this->markTestIncomplete();

        // When a user views their cookie notice settings, they may not have selected 'Analytics'. However, 
        // when they view settings and 'Analytics' is checked_by_default, it will look like the user has already
        // selected 'Analytics'.
    }
}
