<?php

use DuncanMcClean\CookieNotice\UpdateScripts\MigrateToAddonConfig;
use Illuminate\Support\Facades\File;
use Statamic\Facades\Addon;
use Statamic\Facades\Preference;
use Statamic\Facades\Role;
use Statamic\Facades\User;

use function PHPUnit\Framework\assertFileDoesNotExist;

beforeEach(fn () => File::ensureDirectoryExists(base_path('content')));

function runUpdateScript($fqcn, $package = 'duncanmcclean/cookie-notice')
{
    $script = new $fqcn($package);

    $script->update();

    return $script;
}

it('migrates scripts', function () {
    File::put(base_path('content/cookie-notice.yaml'), <<<'YAML'
revision: 5
necessary:
    -
        id: foo
        script_type: other
        inline_javascript: "alert('Hello world!')"
YAML
    );

    runUpdateScript(MigrateToAddonConfig::class);

    $settings = Addon::get('duncanmcclean/cookie-notice')->settings();

    expect($settings->raw())->toBe([
        'revision' => 5,
        'necessary' => [
            ['id' => 'foo', 'script_type' => 'other', 'inline_javascript' => "alert('Hello world!')"],
        ],
    ]);

    assertFileDoesNotExist(base_path('content/cookie-notice.yaml'));
});

it('updates permissions', function () {
    Role::make('test')
        ->permissions(['access cp', 'manage scripts'])
        ->save();

    runUpdateScript(MigrateToAddonConfig::class);

    expect(Role::find('test')->permissions()->all())->toBe([
        'access cp',
        'edit cookie-notice settings',
    ]);
});

it('updates default nav preferences', function () {
    Preference::default()
        ->set('nav', [
            'tools' => [
                'reorder' => [
                    'tools::cookie_notice::scripts',
                    'tools::forms',
                    'tools::updates',
                    'tools::addons',
                ],
            ],
        ])
        ->save();

    runUpdateScript(MigrateToAddonConfig::class);

    expect(Preference::default()->get('nav'))->toBe([
        'tools' => [
            'reorder' => [
                'tools::cookie_notice',
                'tools::forms',
                'tools::updates',
                'tools::addons',
            ],
        ],
    ]);
});

it('updates role nav preferences', function () {
    Role::make('test')
        ->setPreference('nav', [
            'tools' => [
                'reorder' => [
                    'tools::cookie_notice::scripts',
                    'tools::forms',
                    'tools::updates',
                    'tools::addons',
                ],
            ],
        ])
        ->save();

    runUpdateScript(MigrateToAddonConfig::class);

    expect(Role::find('test')->getPreference('nav'))->toBe([
        'tools' => [
            'reorder' => [
                'tools::cookie_notice',
                'tools::forms',
                'tools::updates',
                'tools::addons',
            ],
        ],
    ]);
});

it('updates user nav preferences', function () {
    User::make()
        ->email('hoff@example.com')
        ->setPreference('nav', [
            'tools' => [
                'reorder' => [
                    'tools::cookie_notice::scripts',
                    'tools::forms',
                    'tools::updates',
                    'tools::addons',
                ],
            ],
        ])
        ->save();

    runUpdateScript(MigrateToAddonConfig::class);

    expect(User::findByEmail('hoff@example.com')->getPreference('nav'))->toBe([
        'tools' => [
            'reorder' => [
                'tools::cookie_notice',
                'tools::forms',
                'tools::updates',
                'tools::addons',
            ],
        ],
    ]);
});
