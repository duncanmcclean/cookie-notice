<?php

use DuncanMcClean\CookieNotice\Scripts\Scripts;
use Statamic\Facades\Addon;

beforeEach(function () {
    Addon::get('duncanmcclean/cookie-notice')->settings()->delete();
});

it('ensures gtm_consent_types key is saved as empty array, to prevent it falling back to default value', function () {
    $addon = Addon::get('duncanmcclean/cookie-notice');

    $addon->settings()->set([
        'revision' => '2',
        'analytics' => [
            [
                'script_type' => 'google-tag-manager',
                'gtm_container_id' => 'GTM-123456CN',
                'gtm_consent_types' => [],
            ],
        ],
    ])->save();

    expect(Scripts::data())->toBe([
        'revision' => '2',
        'analytics' => [
            [
                'script_type' => 'google-tag-manager',
                'gtm_container_id' => 'GTM-123456CN',
                'gtm_consent_types' => [],
            ],
        ],
    ]);
});
