<?php

use DuncanMcClean\CookieNotice\Scripts\Scripts;
use Statamic\Facades\Addon;

beforeEach(function () {
    Addon::get('duncanmcclean/cookie-notice')->settings()->delete();
});

it('gets the scripts data', function () {
    Addon::get('duncanmcclean/cookie-notice')
        ->settings()
        ->set([
            'revision' => '5',
            'necessary' => [
                [
                    'script_type' => 'google-tag-manager',
                    'gtm_container_id' => 'GTM-123456CN',
                    'gtm_consent_types' => ['ad_storage', 'ad_user_data', 'ad_personalization', 'analytics_storage'],
                ],
            ],
        ])
        ->save();

    expect(Scripts::data())
        ->toBeArray()
        ->toBe([
            'revision' => '5',
            'necessary' => [
                [
                    'script_type' => 'google-tag-manager',
                    'gtm_container_id' => 'GTM-123456CN',
                    'gtm_consent_types' => ['ad_storage', 'ad_user_data', 'ad_personalization', 'analytics_storage'],
                ],
            ],
        ]);
});

it("returns empty array when addon settings aren't set", function () {
    expect(Scripts::data())
        ->toBeArray()
        ->toBe([]);
});

it('gets the revision', function () {
    Addon::get('duncanmcclean/cookie-notice')
        ->settings()
        ->set([
            'revision' => '5',
            'necessary' => [
                [
                    'script_type' => 'google-tag-manager',
                    'gtm_container_id' => 'GTM-123456CN',
                ],
            ],
        ])
        ->save();

    expect(Scripts::revision())->toBe(5);
});

it('gets the scripts', function () {
    Addon::get('duncanmcclean/cookie-notice')
        ->settings()
        ->set([
            'revision' => '5',
            'necessary' => [
                [
                    'script_type' => 'google-tag-manager',
                    'gtm_container_id' => 'GTM-123456CN',
                    'gtm_consent_types' => ['ad_storage', 'ad_user_data', 'ad_personalization', 'analytics_storage'],
                ],
            ],
        ])
        ->save();

    expect(Scripts::scripts())->toBe([
        'necessary' => [
            [
                'script_type' => 'google-tag-manager',
                'gtm_container_id' => 'GTM-123456CN',
                'gtm_consent_types' => ['ad_storage', 'ad_user_data', 'ad_personalization', 'analytics_storage'],
            ],
        ],
    ]);
});
