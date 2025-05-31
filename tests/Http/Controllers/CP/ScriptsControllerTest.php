<?php

use DuncanMcClean\CookieNotice\Scripts\Scripts;
use Statamic\Facades\Role;
use Statamic\Facades\User;

use function Pest\Laravel\actingAs;

it('cant render manage scripts page without permission', function () {
    $role = Role::make('author')->addPermission('access control panel')->save();

    actingAs(User::make()->assignRole($role)->save())
        ->get('/cp/cookie-notice/scripts')
        ->assertRedirect('/cp');
});

it('renders the manage scripts page', function () {
    actingAs(User::make()->makeSuper()->save())
        ->get('/cp/cookie-notice/scripts')
        ->assertOk()
        ->assertSee('Manage Scripts');
});

it('saves the scripts', function () {
    expect(Scripts::data())->toBe([]);

    actingAs(User::make()->makeSuper()->save())
        ->patch('/cp/cookie-notice/scripts', [
            'values' => [
                'revision' => '2',
                'necessary' => [
                    [
                        'script_type' => 'other',
                        'gtm_container_id' => null,
                        'meta_pixel_id' => null,
                        'inline_javascript' => ['code' => 'alert("Hello, world!")', 'mode' => 'javascript'],
                        'gtm_consent_types' => ['ad_storage', 'ad_user_data', 'ad_personalization', 'analytics_storage'],
                        'spacer' => null,
                    ],
                ],
                'analytics' => [
                    [
                        'script_type' => 'google-tag-manager',
                        'gtm_container_id' => 'GTM-123456CN',
                        'meta_pixel_id' => null,
                        'inline_javascript' => ['code' => null, 'mode' => 'javascript'],
                        'gtm_consent_types' => ['ad_storage', 'ad_user_data', 'ad_personalization', 'analytics_storage'],
                        'spacer' => null,
                    ],
                    [
                        'script_type' => 'meta-pixel',
                        'gtm_container_id' => null,
                        'meta_pixel_id' => '123456789123456',
                        'inline_javascript' => ['code' => null, 'mode' => 'javascript'],
                        'gtm_consent_types' => ['ad_storage', 'ad_user_data', 'ad_personalization', 'analytics_storage'],
                        'spacer' => null,
                    ],
                ],
            ],
        ])
        ->assertOk()
        ->assertJson(['message' => 'Scripts saved']);

    expect(Scripts::data())->toBe([
        'revision' => '2',
        'necessary' => [
            [
                'script_type' => 'other',
                'inline_javascript' => 'alert("Hello, world!")',
            ],
        ],
        'analytics' => [
            [
                'script_type' => 'google-tag-manager',
                'gtm_container_id' => 'GTM-123456CN',
                'gtm_consent_types' => ['ad_storage', 'ad_user_data', 'ad_personalization', 'analytics_storage'],
            ],
            [
                'script_type' => 'meta-pixel',
                'meta_pixel_id' => '123456789123456',
            ],
        ],
    ]);
});

it('ensures gtm_consent_types key is saved as empty array, to prevent it falling back to default value', function () {
    expect(Scripts::data())->toBe([]);

    actingAs(User::make()->makeSuper()->save())
        ->patch('/cp/cookie-notice/scripts', [
            'values' => [
                'revision' => '2',
                'necessary' => [],
                'analytics' => [
                    [
                        'script_type' => 'google-tag-manager',
                        'gtm_container_id' => 'GTM-123456CN',
                        'meta_pixel_id' => null,
                        'inline_javascript' => ['code' => null, 'mode' => 'javascript'],
                        'gtm_consent_types' => [],
                        'spacer' => null,
                    ],
                ],
            ],
        ])
        ->assertOk()
        ->assertJson(['message' => 'Scripts saved']);

    expect(Scripts::data())->toBe([
        'revision' => '2',
        'necessary' => [],
        'analytics' => [
            [
                'script_type' => 'google-tag-manager',
                'gtm_container_id' => 'GTM-123456CN',
                'gtm_consent_types' => [],
            ],
        ],
    ]);
});

it('does not save the scripts when script has invalid GTM format', function () {
    expect(Scripts::data())->toBe([]);

    actingAs(User::make()->makeSuper()->save())
        ->patch('/cp/cookie-notice/scripts', [
            'values' => [
                'revision' => '1',
                'analytics' => [
                    [
                        'script_type' => 'google-tag-manager',
                        'gtm_container_id' => 'GMT-12345',
                        'meta_pixel_id' => null,
                        'inline_javascript' => ['code' => null, 'mode' => 'javascript'],
                        'spacer' => null,
                    ],
                ],
            ],
        ])
        ->assertSessionHasErrors([
            'analytics.0.gtm_container_id' => 'This must be a valid Google Tag Manager Container ID.',
        ]);

    expect(Scripts::data())->toBe([]);
});

it('does not save the scripts when script has invalid Meta Pixel ID format', function () {
    expect(Scripts::data())->toBe([]);

    actingAs(User::make()->makeSuper()->save())
        ->patch('/cp/cookie-notice/scripts', [
            'values' => [
                'revision' => '1',
                'analytics' => [
                    [
                        'script_type' => 'meta-pixel',
                        'gtm_container_id' => null,
                        'meta_pixel_id' => '12345678912FB',
                        'inline_javascript' => ['code' => null, 'mode' => 'javascript'],
                        'spacer' => null,
                    ],
                ],
            ],
        ])
        ->assertSessionHasErrors([
            'analytics.0.meta_pixel_id' => 'This must be a valid Meta Pixel ID.',
        ]);

    expect(Scripts::data())->toBe([]);
});

it('does not save the scripts when script contains script tag', function () {
    expect(Scripts::data())->toBe([]);

    actingAs(User::make()->makeSuper()->save())
        ->patch('/cp/cookie-notice/scripts', [
            'values' => [
                'revision' => '1',
                'necessary' => [
                    [
                        'script_type' => 'other',
                        'gtm_container_id' => null,
                        'meta_pixel_id' => null,
                        'inline_javascript' => ['code' => '<script>alert("Hello, world!")</script>', 'mode' => 'javascript'],
                        'spacer' => null,
                    ],
                ],
            ],
        ])
        ->assertSessionHasErrors([
            'necessary.0.inline_javascript' => 'This field must not contain `<script>` tags.',
        ]);

    expect(Scripts::data())->toBe([]);
});
