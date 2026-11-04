<?php

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Statamic\Facades\Addon;
use Statamic\Facades\Parse;

beforeEach(function () {
    File::ensureDirectoryExists(resource_path('views/partials'));
});

it('renders the cookie consent widget', function () {
    expect((string) Parse::template('<body>{{ cookie_notice:widget }}</body>', trusted: true))
        ->toContain('<!-- Start of Cookie Notice Widget -->')
        ->toContain('window.CookieNotice.boot(');
});

it('encodes the widget config so it is safe inside a script tag', function (string $description) {
    Config::set('cookie-notice.consent_groups', [
        ['name' => 'Analytics', 'handle' => 'analytics', 'description' => $description],
    ]);

    $html = (string) Parse::template('<body>{{ cookie_notice:widget }}</body>', trusted: true);

    preg_match('/window\.CookieNotice\.boot\(\s*document\.getElementById\(\'cookie-notice\'\),\s*(\{.*?\})\s*\);/s', $html, $matches);

    expect($matches[1])
        ->not->toContain('</script>')
        ->not->toContain('"description":"'.$description.'"')
        ->and(json_decode($matches[1], true)['consent_groups'][0]['description'])->toBe($description);
})->with([
    'double quotes' => 'Say "hi"',
    'single quotes' => "Don't track me",
    'newline' => "First line\nSecond line",
    'script tag' => 'Analytics</script><script>alert(1)</script>',
]);

it('disables checkboxes for consent groups with checkbox_disabled', function () {
    $html = (string) Parse::template('<body>{{ cookie_notice:widget }}</body>', trusted: true);

    expect($html)
        ->toMatch('/name="group-necessary"[^>]*disabled/')
        ->not->toMatch('/name="group-analytics"[^>]*disabled/');
});

it('renders the cookie consent widget using a custom view', function () {
    Config::set('cookie-notice.widget_view', 'partials.custom-cookie-notice-widget');
    File::put(resource_path('views/partials/custom-cookie-notice-widget.antlers.html'), '<div>Custom Cookie Notice Widget</div>');

    expect((string) Parse::template('<body>{{ cookie_notice:widget }}</body>', trusted: true))
        ->toContain('<div>Custom Cookie Notice Widget</div>');
});

it('does not render the cookie consent widget when live previewing', function () {
    expect((string) Parse::template('<body>{{ cookie_notice:widget }}</body>', ['live_preview' => true], trusted: true))
        ->not->toContain('<!-- Start of Cookie Notice Widget -->')
        ->not->toContain('window.CookieNotice.boot(');
});

it('returns cookie consent javascript', function () {
    expect((string) Parse::template('<head>{{ cookie_notice:scripts }}</head>', trusted: true))
        ->toContain('<!-- Start of Cookie Notice Scripts -->')
        ->toContain('<script>');
});

it('outputs google tag manager scripts', function () {
    Addon::get('duncanmcclean/cookie-notice')->settings()->set([
        'default' => [
            'analytics' => [[
                'script_type' => 'google-tag-manager',
                'gtm_container_id' => 'GTM-123456CN',
                'gtm_consent_types' => ['ad_user_data', 'ad_personalization'],
            ]],
        ],
    ])->save();

    expect((string) Parse::template('<head>{{ cookie_notice:scripts }}</head>', trusted: true))
        ->toContain('<script>window.dataLayer = window.dataLayer || []; function gtag(){dataLayer.push(arguments);}</script>')
        ->toContain("'script','dataLayer','GTM-123456CN'")
        ->toContain('"ad_user_data":"granted"')
        ->toContain('"ad_personalization":"granted"')
        ->not->toContain('"ad_storage":"granted"')
        ->not->toContain('"analytics_storage":"granted"');
});

it('outputs meta pixel scripts', function () {
    Addon::get('duncanmcclean/cookie-notice')->settings()->set([
        'default' => [
            'analytics' => [[
                'script_type' => 'meta-pixel',
                'meta_pixel_id' => '123456789123456',
            ]],
        ],
    ])->save();

    expect((string) Parse::template('<head>{{ cookie_notice:scripts }}</head>', trusted: true))
        ->toContain('<script type="text/plain" data-consent-group="analytics">')
        ->toContain("fbq('init', '123456789123456');");
});

it('outputs inline javascript', function () {
    Addon::get('duncanmcclean/cookie-notice')->settings()->set([
        'default' => [
            'analytics' => [[
                'script_type' => 'other',
                'inline_javascript' => 'console.log("Hello, World!")',
            ]],
        ],
    ])->save();

    expect((string) Parse::template('<head>{{ cookie_notice:scripts }}</head>', trusted: true))
        ->toContain('<script type="text/plain" data-consent-group="analytics">')
        ->toContain('console.log("Hello, World!")');
});

it('does not return cookie consent javascript when live previewing', function () {
    expect((string) Parse::template('<head>{{ cookie_notice:scripts }}</head>', ['live_preview' => true], trusted: true))
        ->not->toContain('<!-- Start of Cookie Notice Scripts -->')
        ->not->toContain('<script>');
});
