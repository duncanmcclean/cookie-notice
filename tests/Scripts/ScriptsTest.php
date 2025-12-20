<?php

use DuncanMcClean\CookieNotice\Scripts\Scripts;
use Statamic\Facades\Addon;
use Statamic\Facades\Site;

beforeEach(function () {
    Addon::get('duncanmcclean/cookie-notice')->settings()->delete();
});

it('gets the scripts data', function () {
    Addon::get('duncanmcclean/cookie-notice')
        ->settings()
        ->set([
            'default' => [
                'revision' => '5',
                'necessary' => [
                    [
                        'script_type' => 'google-tag-manager',
                        'gtm_container_id' => 'GTM-123456CN',
                        'gtm_consent_types' => ['ad_storage', 'ad_user_data', 'ad_personalization', 'analytics_storage'],
                    ],
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

it('gets the scripts data for the current site', function () {
    config()->set('statamic.system.multisite', true);
    config()->set('cookie-notice.configure_scripts_per_site', true);

    Site::setSites([
        'uk' => [
            'name' => 'United Kingdom',
            'locale' => 'en_GB',
            'url' => 'https://example.co.uk',
        ],
        'de' => [
            'name' => 'Germany',
            'locale' => 'de_DE',
            'url' => 'https://example.de',
        ],
    ]);

    Addon::get('duncanmcclean/cookie-notice')
        ->settings()
        ->set([
            'uk' => [
                'revision' => '5',
                'necessary' => [
                    [
                        'script_type' => 'google-tag-manager',
                        'gtm_container_id' => 'GTM-123456CN',
                        'gtm_consent_types' => ['ad_storage', 'ad_user_data', 'ad_personalization', 'analytics_storage'],
                    ],
                ],
            ],
            'de' => [
                'revision' => '2',
                'necessary' => [
                    [
                        'script_type' => 'google-tag-manager',
                        'gtm_container_id' => 'GTM-567899NN',
                        'gtm_consent_types' => ['ad_storage', 'ad_user_data', 'analytics_storage'],
                    ],
                ],
            ],
        ])
        ->save();

    Site::setCurrent('uk');

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

    Site::setCurrent('de');

    expect(Scripts::data())
        ->toBeArray()
        ->toBe([
            'revision' => '2',
            'necessary' => [
                [
                    'script_type' => 'google-tag-manager',
                    'gtm_container_id' => 'GTM-567899NN',
                    'gtm_consent_types' => ['ad_storage', 'ad_user_data', 'analytics_storage'],
                ],
            ],
        ]);
});

it('always gets the scripts data for the default site when configure_scripts_per_site is false', function () {
    config()->set('statamic.system.multisite', true);
    config()->set('cookie-notice.configure_scripts_per_site', false);

    Site::setSites([
        'uk' => [
            'name' => 'United Kingdom',
            'locale' => 'en_GB',
            'url' => 'https://example.co.uk',
        ],
        'de' => [
            'name' => 'Germany',
            'locale' => 'de_DE',
            'url' => 'https://example.de',
        ],
    ]);

    Addon::get('duncanmcclean/cookie-notice')
        ->settings()
        ->set([
            'uk' => [
                'revision' => '5',
                'necessary' => [
                    [
                        'script_type' => 'google-tag-manager',
                        'gtm_container_id' => 'GTM-123456CN',
                        'gtm_consent_types' => ['ad_storage', 'ad_user_data', 'ad_personalization', 'analytics_storage'],
                    ],
                ],
            ],
            'de' => [
                'revision' => '2',
                'necessary' => [
                    [
                        'script_type' => 'google-tag-manager',
                        'gtm_container_id' => 'GTM-567899NN',
                        'gtm_consent_types' => ['ad_storage', 'ad_user_data', 'analytics_storage'],
                    ],
                ],
            ],
        ])
        ->save();

    Site::setCurrent('uk');

    expect(Scripts::data())
        ->toBeArray()
        ->toBe($uk = [
            'revision' => '5',
            'necessary' => [
                [
                    'script_type' => 'google-tag-manager',
                    'gtm_container_id' => 'GTM-123456CN',
                    'gtm_consent_types' => ['ad_storage', 'ad_user_data', 'ad_personalization', 'analytics_storage'],
                ],
            ],
        ]);

    Site::setCurrent('de');

    expect(Scripts::data())
        ->toBeArray()
        ->toBe($uk);
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
            'default' => [
                'revision' => '5',
                'necessary' => [
                    [
                        'script_type' => 'google-tag-manager',
                        'gtm_container_id' => 'GTM-123456CN',
                    ],
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
            'default' => [
                'revision' => '5',
                'necessary' => [
                    [
                        'script_type' => 'google-tag-manager',
                        'gtm_container_id' => 'GTM-123456CN',
                        'gtm_consent_types' => ['ad_storage', 'ad_user_data', 'ad_personalization', 'analytics_storage'],
                    ],
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
