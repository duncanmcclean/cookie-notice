<?php

use Statamic\Facades\Addon;
use Statamic\Facades\Site;
use Statamic\Facades\User;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Laravel\patch;

beforeEach(function () {
    Addon::get('duncanmcclean/cookie-notice')->settings()->delete();

    config()->set('statamic.editions.pro', true);

    $user = tap(User::make()->makeSuper())->save();
    actingAs($user);
});

it('loads existing settings in edit', function () {
    Addon::get('duncanmcclean/cookie-notice')
        ->settings()
        ->set([
            'default' => [
                'revision' => '5',
                'necessary' => [
                    [
                        'script_type' => 'google-tag-manager',
                        'gtm_container_id' => 'GTM-123456CN',
                        'gtm_consent_types' => ['ad_storage', 'analytics_storage'],
                    ],
                ],
            ],
        ])
        ->save();

    get(cp_route('cookie-notice.scripts.edit'), ['Accept' => 'application/json'])
        ->assertOk()
        ->assertJson([
            'initialSite' => 'default',
            'initialValues' => [
                'revision' => '5',
            ],
        ])
        ->assertJsonPath('initialValues.necessary.0.script_type', 'google-tag-manager')
        ->assertJsonPath('initialValues.necessary.0.gtm_container_id', 'GTM-123456CN')
        ->assertJsonCount(1, 'initialValues.necessary');
});

it('shows only default site when multisite is disabled', function () {
    config()->set('statamic.system.multisite', true);

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

    config()->set('cookie-notice.configure_scripts_per_site', false);

    get(cp_route('cookie-notice.scripts.edit'), ['Accept' => 'application/json'])
        ->assertOk()
        ->assertJsonCount(1, 'initialLocalizations')
        ->assertJsonPath('initialLocalizations.0.handle', 'uk');
});

it('shows all sites when multisite is enabled and configure_scripts_per_site is true', function () {
    config()->set('statamic.system.multisite', true);

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

    config()->set('cookie-notice.configure_scripts_per_site', true);

    get(cp_route('cookie-notice.scripts.edit'), ['Accept' => 'application/json'])
        ->assertOk()
        ->assertJsonCount(2, 'initialLocalizations')
        ->assertJsonPath('initialLocalizations.0.handle', 'uk')
        ->assertJsonPath('initialLocalizations.1.handle', 'de');
});

it('loads site-specific settings when multisite is enabled', function () {
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
                        'gtm_container_id' => 'GTM-UK123',
                    ],
                ],
            ],
            'de' => [
                'revision' => '3',
                'necessary' => [
                    [
                        'script_type' => 'google-tag-manager',
                        'gtm_container_id' => 'GTM-DE123',
                    ],
                ],
            ],
        ])
        ->save();

    get(cp_route('cookie-notice.scripts.edit', ['site' => 'de']), ['Accept' => 'application/json'])
        ->assertOk()
        ->assertJson([
            'initialSite' => 'de',
            'initialValues' => [
                'revision' => '3',
            ],
        ])
        ->assertJsonPath('initialValues.necessary.0.gtm_container_id', 'GTM-DE123');
});

it('can update settings', function () {
    patch(cp_route('cookie-notice.scripts.update'), [
        'revision' => '10',
        'necessary' => [
            [
                'script_type' => 'google-tag-manager',
                'gtm_container_id' => 'GTM-NEW123',
                'gtm_consent_types' => ['analytics_storage'],
            ],
        ],
    ]);

    $settings = Addon::get('duncanmcclean/cookie-notice')->settings()->raw();

    expect($settings['default']['revision'])->toBe('10')
        ->and($settings['default']['necessary'])->toHaveCount(1)
        ->and($settings['default']['necessary'][0])->toBe([
            'script_type' => 'google-tag-manager',
            'gtm_container_id' => 'GTM-NEW123',
            'gtm_consent_types' => ['analytics_storage'],
        ]);
});

it('can update site-specific settings when multisite is enabled', function () {
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
                'necessary' => [],
            ],
        ])
        ->save();

    patch(cp_route('cookie-notice.scripts.update', ['site' => 'de']), [
        'site' => 'de',
        'revision' => '2',
        'necessary' => [
            [
                'script_type' => 'google-tag-manager',
                'gtm_container_id' => 'GTM-DE999',
                'gtm_consent_types' => ['ad_storage'],
            ],
        ],
    ]);

    $settings = Addon::get('duncanmcclean/cookie-notice')->settings()->raw();

    expect($settings['uk']['revision'])->toBe('5')
        ->and($settings['uk']['necessary'])->toBe([])
        ->and($settings['de']['revision'])->toBe('2')
        ->and($settings['de']['necessary'])->toHaveCount(1)
        ->and($settings['de']['necessary'][0])->toBe([
            'script_type' => 'google-tag-manager',
            'gtm_container_id' => 'GTM-DE999',
            'gtm_consent_types' => ['ad_storage'],
        ]);
});

it('validates required fields on update', function () {
    patch(cp_route('cookie-notice.scripts.update'), [
        'necessary' => [],
    ])->assertSessionHasErrors(['revision']);
});

it('validates GTM container ID format', function () {
    patch(cp_route('cookie-notice.scripts.update'), [
        'revision' => '1',
        'necessary' => [
            [
                'script_type' => 'google-tag-manager',
                'gtm_container_id' => 'INVALID-ID',
            ],
        ],
    ])->assertSessionHasErrors(['necessary.0.gtm_container_id']);
});

it('validates Meta Pixel ID format', function () {
    patch(cp_route('cookie-notice.scripts.update'), [
        'revision' => '1',
        'necessary' => [
            [
                'script_type' => 'meta-pixel',
                'meta_pixel_id' => 'invalid',
            ],
        ],
    ])->assertSessionHasErrors(['necessary.0.meta_pixel_id']);
});

it('validates inline JavaScript without script tags', function () {
    patch(cp_route('cookie-notice.scripts.update'), [
        'revision' => '1',
        'necessary' => [
            [
                'script_type' => 'other',
                'inline_javascript' => [
                    'code' => '<script>alert("test")</script>',
                    'mode' => 'javascript',
                ],
            ],
        ],
    ])->assertSessionHasErrors(['necessary.0.inline_javascript']);
});

it('allows valid inline JavaScript without script tags', function () {
    patch(cp_route('cookie-notice.scripts.update'), [
        'revision' => '1',
        'necessary' => [
            [
                'script_type' => 'other',
                'inline_javascript' => [
                    'code' => 'console.log("test");',
                    'mode' => 'javascript',
                ],
            ],
        ],
    ]);

    $settings = Addon::get('duncanmcclean/cookie-notice')->settings()->raw();

    expect($settings['default']['necessary'][0]['inline_javascript'])->toBe('console.log("test");');
});

it('uses default site when multisite is disabled', function () {
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

    Site::setSelected('de');

    patch(cp_route('cookie-notice.scripts.update'), [
        'revision' => '1',
        'necessary' => [],
    ]);

    $settings = Addon::get('duncanmcclean/cookie-notice')->settings()->raw();

    expect($settings)->toHaveKey('uk')
        ->and($settings)->not->toHaveKey('de');
});

it('determines site from request parameter when multisite', function () {
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

    Site::setSelected('uk');

    get(cp_route('cookie-notice.scripts.edit', ['site' => 'de']), ['Accept' => 'application/json'])
        ->assertOk()
        ->assertJsonPath('initialSite', 'de');
});

it('uses selected site when no site parameter provided and multisite', function () {
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

    Site::setSelected('de');

    get(cp_route('cookie-notice.scripts.edit'), ['Accept' => 'application/json'])
        ->assertOk()
        ->assertJsonPath('initialSite', 'de');
});

it('includes all required props', function () {
    get(cp_route('cookie-notice.scripts.edit'), ['Accept' => 'application/json'])
        ->assertOk()
        ->assertJsonStructure([
            'icon',
            'blueprint',
            'initialSite',
            'initialValues',
            'initialMeta',
            'initialLocalizations',
            'action',
        ]);
});

it('supports multiple consent types for GTM', function () {
    patch(cp_route('cookie-notice.scripts.update'), [
        'revision' => '1',
        'necessary' => [
            [
                'script_type' => 'google-tag-manager',
                'gtm_container_id' => 'GTM-ABC123',
                'gtm_consent_types' => ['ad_storage', 'ad_user_data', 'ad_personalization', 'analytics_storage'],
            ],
        ],
    ]);

    $settings = Addon::get('duncanmcclean/cookie-notice')->settings()->raw();

    expect($settings['default']['necessary'][0]['gtm_consent_types'])
        ->toBeArray()
        ->toHaveCount(4)
        ->toContain('ad_storage', 'ad_user_data', 'ad_personalization', 'analytics_storage');
});

it('can save multiple scripts in one consent group', function () {
    patch(cp_route('cookie-notice.scripts.update'), [
        'revision' => '1',
        'necessary' => [
            [
                'script_type' => 'google-tag-manager',
                'gtm_container_id' => 'GTM-ABC123',
                'gtm_consent_types' => ['analytics_storage'],
            ],
            [
                'script_type' => 'meta-pixel',
                'meta_pixel_id' => '123456789012345',
            ],
        ],
    ]);

    $settings = Addon::get('duncanmcclean/cookie-notice')->settings()->raw();

    expect($settings['default']['necessary'])->toHaveCount(2);
});

it('ensures gtm_consent_types key is saved as empty array when not provided to prevent it falling back to default value', function () {
    patch(cp_route('cookie-notice.scripts.update'), [
        'revision' => '2',
        'analytics' => [
            [
                'script_type' => 'google-tag-manager',
                'gtm_container_id' => 'GTM-123456CN',
                // gtm_consent_types is intentionally not provided
            ],
        ],
    ]);

    $settings = Addon::get('duncanmcclean/cookie-notice')->settings()->raw();

    expect($settings['default']['analytics'][0])->toHaveKey('gtm_consent_types')
        ->and($settings['default']['analytics'][0]['gtm_consent_types'])->toBe([]);
});
