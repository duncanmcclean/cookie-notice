<?php

use DuncanMcClean\CookieNotice\Tags\CookieNoticeTag;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use function PHPUnit\Framework\assertIsString;
use function PHPUnit\Framework\assertStringContainsString;
use function PHPUnit\Framework\assertTrue;
use Statamic\Facades\Antlers;

$tag = null;

beforeEach(function () use (&$tag) {
    $tag = (new CookieNoticeTag())
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

    File::makeDirectory(public_path('vendor/cookie-notice/build'), 0755, true, true);
    File::put(public_path('vendor/cookie-notice/build/manifest.json'), json_encode([
        'resources/css/cookie-notice.css' => [
            'file' => 'assets/cookie-notice-testing.css',
            'isEntry' => true,
            'src' => 'resources/css/cookie-notice.css',
        ],
    ]));

    File::makeDirectory(public_path('vendor/cookie-notice/build/assets'), 0755, true, true);
    File::put(public_path('vendor/cookie-notice/build/assets/cookie-notice-testing.css'), '');
});

test('cookie notice tag is registered', function () use (&$tag) {
    assertTrue(isset($this->app['statamic.tags']['cookie_notice']));
});

test('can see cookie notice', function () use (&$tag) {
    $tag->setParameters([]);

    $notice = $tag->index()->render();

    assertIsString($notice);
    assertStringContainsString('id="group_necessary"', $notice);
    assertStringContainsString('id="group_statistics"', $notice);
    assertStringContainsString('id="group_marketing"', $notice);
});
