<?php

namespace DuncanMcClean\CookieNotice\Tests\Tags;

use DuncanMcClean\CookieNotice\Tags\CookieNoticeTag;
use DuncanMcClean\CookieNotice\Tests\TestCase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
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

        File::makeDirectory(public_path('vendor/cookie-notice/css'), 0755, true, true);
        File::put(public_path('vendor/cookie-notice/css/cookie-notice.css'), '');
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
    public function cookie_notice_settings_should_not_select_checkboxes_that_are_selected_by_default()
    {
        // TODO
        $this->markTestIncomplete();

        // When a user views their cookie notice settings, they may not have selected 'Analytics'. However,
        // when they view settings and 'Analytics' is checked_by_default, it will look like the user has already
        // selected 'Analytics'.
    }
}
