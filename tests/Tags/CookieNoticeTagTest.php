<?php

use DuncanMcClean\CookieNotice\Scripts\Scripts;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Statamic\Facades\Parse;

beforeEach(function () {
    File::ensureDirectoryExists(resource_path('views/partials'));
});

it('renders the cookie consent widget', function () {
    expect((string) Parse::template('<body>{{ cookie_notice:widget }}</body>'))
        ->toContain('<!-- Start of Cookie Notice Widget -->')
        ->toContain('window.CookieNotice.boot(');
});

it('renders the cookie consent widget using a custom view', function () {
    Config::set('cookie-notice.widget_view', 'partials.custom-cookie-notice-widget');
    File::put(resource_path('views/partials/custom-cookie-notice-widget.antlers.html'), '<div>Custom Cookie Notice Widget</div>');

    expect((string) Parse::template('<body>{{ cookie_notice:widget }}</body>'))
        ->toContain('<div>Custom Cookie Notice Widget</div>');
});

it('does not render the cookie consent widget when live previewing', function () {
    expect((string) Parse::template('<body>{{ cookie_notice:widget }}</body>', ['live_preview' => true]))
        ->not->toContain('<!-- Start of Cookie Notice Widget -->')
        ->not->toContain('window.CookieNotice.boot(');
});

it('returns cookie consent javascript', function () {
    expect((string) Parse::template('<head>{{ cookie_notice:scripts }}</head>'))
        ->toContain('<!-- Start of Cookie Notice Scripts -->')
        ->toContain('<script>');
});

it('outputs google tag manager scripts', function () {
    Scripts::save([
        'analytics' => [[
            'script_type' => 'google-tag-manager',
            'gtm_container_id' => 'GTM-123456CN',
            'gtm_consent_types' => ['ad_user_data', 'ad_personalization'],
        ]],
    ]);

    expect((string) Parse::template('<head>{{ cookie_notice:scripts }}</head>'))
        ->toContain('<script>window.dataLayer = window.dataLayer || []; function gtag(){dataLayer.push(arguments);}</script>')
        ->toContain("'script','dataLayer','GTM-123456CN'")
        ->toContain('"ad_user_data":"granted"')
        ->toContain('"ad_personalization":"granted"')
        ->not->toContain('"ad_storage":"granted"')
        ->not->toContain('"analytics_storage":"granted"');
});

it('outputs meta pixel scripts', function () {
    Scripts::save([
        'analytics' => [[
            'script_type' => 'meta-pixel',
            'meta_pixel_id' => '123456789123456',
        ]],
    ]);

    expect((string) Parse::template('<head>{{ cookie_notice:scripts }}</head>'))
        ->toContain('<script type="text/plain" data-consent-group="analytics">')
        ->toContain("fbq('init', '123456789123456');");
});

it('outputs inline javascript', function () {
    Scripts::save([
        'analytics' => [[
            'script_type' => 'other',
            'inline_javascript' => 'console.log("Hello, World!")',
        ]],
    ]);

    expect((string) Parse::template('<head>{{ cookie_notice:scripts }}</head>'))
        ->toContain('<script type="text/plain" data-consent-group="analytics">')
        ->toContain('console.log("Hello, World!")');
});

it('does not return cookie consent javascript when live previewing', function () {
    expect((string) Parse::template('<head>{{ cookie_notice:scripts }}</head>', ['live_preview' => true]))
        ->not->toContain('<!-- Start of Cookie Notice Scripts -->')
        ->not->toContain('<script>');
});
