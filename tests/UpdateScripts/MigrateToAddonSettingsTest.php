<?php

use DuncanMcClean\CookieNotice\UpdateScripts\MigrateToAddonSettings;
use Illuminate\Support\Facades\File;
use Statamic\Facades\Addon;
use Statamic\Testing\Concerns\RunsUpdateScripts;

use function PHPUnit\Framework\assertFileDoesNotExist;

uses(RunsUpdateScripts::class);

beforeEach(fn () => File::ensureDirectoryExists(base_path('content')));

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

    $this->runUpdateScript(MigrateToAddonSettings::class);

    $settings = Addon::get('duncanmcclean/cookie-notice')->settings();

    expect($settings->raw())->toBe([
        'default' => [
            'revision' => 5,
            'necessary' => [
                ['id' => 'foo', 'script_type' => 'other', 'inline_javascript' => "alert('Hello world!')"],
            ],
        ],
    ]);

    assertFileDoesNotExist(base_path('content/cookie-notice.yaml'));
});
