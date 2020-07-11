<?php

namespace DoubleThreeDigital\CookieNotice\Tests\Http\Controllers;

use DoubleThreeDigital\CookieNotice\Tests\TestCase;
use Illuminate\Support\Facades\Config;

class CookieNoticeActionControllerTest extends TestCase
{
    /** @test */
    public function can_update_consent()
    {
        $this
            ->post(route('statamic.cookie-notice.update'), [
                'group_necessary' => 'on',
                'group_statistics' => 'on',
            ])
            ->assertRedirect()
            ->assertCookie(Config::get('cookie-notice.cookie_name'));    
    }
}