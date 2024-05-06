<?php

use DuncanMcClean\CookieNotice\Scripts\Scripts;
use Illuminate\Support\Facades\File;
use Statamic\Facades\YAML;

it('gets the scripts data', function () {
    File::shouldReceive('exists')
        ->with(storage_path('statamic/addons/cookie-notice/scripts.yaml'))
        ->andReturn(true);

    YAML::shouldReceive('file')
        ->andReturnSelf()
        ->shouldReceive('parse')
        ->andReturn([
            'revision' => '5',
            'necessary' => [
                [
                    'type' => 'google-tag-manager',
                    'gtm_container_id' => 'GTM-123456CN',
                ],
            ],
        ]);

    $data = Scripts::data();

    expect($data)->toBeArray()->toBe([
        'revision' => '5',
        'necessary' => [
            [
                'type' => 'google-tag-manager',
                'gtm_container_id' => 'GTM-123456CN',
            ],
        ],
    ]);
});

it('returns empty array when scripts file is missing', function () {
    File::shouldReceive('exists')
        ->with(storage_path('statamic/addons/cookie-notice/scripts.yaml'))
        ->andReturn(false);

    YAML::shouldReceive('file')->never();

    $data = Scripts::data();

    expect($data)->toBeArray()->toBe([]);
});

it('gets the revision', function () {
    File::shouldReceive('exists')
        ->with(storage_path('statamic/addons/cookie-notice/scripts.yaml'))
        ->andReturn(true);

    YAML::shouldReceive('file')
        ->andReturnSelf()
        ->shouldReceive('parse')
        ->andReturn([
            'revision' => '5',
            'necessary' => [
                [
                    'type' => 'google-tag-manager',
                    'gtm_container_id' => 'GTM-123456CN',
                ],
            ],
        ]);

    expect(Scripts::revision())->toBe(5);
});

it('gets the scripts', function () {
    File::shouldReceive('exists')
        ->with(storage_path('statamic/addons/cookie-notice/scripts.yaml'))
        ->andReturn(true);

    YAML::shouldReceive('file')
        ->andReturnSelf()
        ->shouldReceive('parse')
        ->andReturn([
            'revision' => '5',
            'necessary' => [
                [
                    'type' => 'google-tag-manager',
                    'gtm_container_id' => 'GTM-123456CN',
                ],
            ],
        ]);

    expect(Scripts::scripts())->toBe([
        'necessary' => [
            [
                'type' => 'google-tag-manager',
                'gtm_container_id' => 'GTM-123456CN',
            ],
        ],
    ]);
});

it('saves the scripts data', function () {
    YAML::shouldReceive('dump')
        ->with(['necessary' => [
            [
                'type' => 'google-tag-manager',
                'gtm_container_id' => 'GTM-123456CN',
            ],
        ]])
        ->andReturn('yaml');

    File::shouldReceive('ensureDirectoryExists')
        ->with(dirname(storage_path('statamic/addons/cookie-notice/scripts.yaml')))
        ->once();

    File::shouldReceive('put')
        ->with(storage_path('statamic/addons/cookie-notice/scripts.yaml'), 'yaml')
        ->once();

    Scripts::save(['necessary' => [
        [
            'type' => 'google-tag-manager',
            'gtm_container_id' => 'GTM-123456CN',
        ],
    ]]);
});
