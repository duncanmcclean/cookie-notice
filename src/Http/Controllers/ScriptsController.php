<?php

namespace DuncanMcClean\CookieNotice\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Statamic\Facades\YAML;
use Statamic\Fields\Blueprint;

class ScriptsController
{
    public function edit()
    {
        File::ensureDirectoryExists(storage_path('statamic/addons/cookie-notice'));
        $yaml = File::get(storage_path('statamic/addons/cookie-notice/scripts.yaml'));

        // Get an array of values from the item that you want to be populated
        // in the form. eg. ['title' => 'My Product', 'slug' => 'my-product']
        $values = YAML::parse($yaml);

        // Get a blueprint. This might come from an actual blueprint yaml file
        // or even defined in this class. Read more about blueprints below.
        $blueprint = $this->getBlueprint();

        // You'll probably prefer chaining all of that.
        $fields = $blueprint->fields()->addValues($values)->preProcess();

        // The vue component will need these three values at a minimum.
        return view('cookie-notice::cp.scripts', [
            'blueprint' => $blueprint->toPublishArray(),
            'values'    => $fields->values(),
            'meta'      => $fields->meta(),
        ]);
    }

    public function update(Request $request)
    {
        // todo: validate request (ensure we have valid pixel ids, gtm thingys, etc)

        $blueprint = $this->getBlueprint();

        // Get a Fields object, and populate it with the submitted values.
        $fields = $blueprint->fields()->addValues($request->all());

        // Perform validation. Like Laravel's standard validation, if it fails,
        // a 422 response will be sent back with all the validation errors.
        $fields->validate();

        // Perform post-processing. This will convert values the Vue components
        // were using into values suitable for putting into storage.
        $values = $fields->process()->values();

        // Do something with the values. Here we'll update the product model.
        $yaml = YAML::dump($values->all());

        File::ensureDirectoryExists(storage_path('statamic/addons/cookie-notice'));
        File::put(storage_path('statamic/addons/cookie-notice/scripts.yaml'), $yaml);

        return response()->json(['message' => 'Scripts saved']);
    }

    // todo: document this method
    // todo: add tests
    // todo: revision field

    protected function getBlueprint(): Blueprint
    {
        return app(Blueprint::class)->setContents([
            'tabs' => collect(config('cookie-notice.consent_groups'))->mapWithKeys(function ($consentGroup) {
                $fields = [
                    [
                        'handle' => "{$consentGroup['handle']}",
                        'field' => [
                            'fields' => [
                                [
                                    'handle' => 'script_type',
                                    'field' => [
                                        'options' => [
                                            'google-tag-manager' => __('Google Tag Manager'),
                                            'meta-pixel' => __('Meta Pixel'),
                                            'other' => __('Other (JavaScript)'),
                                        ],
                                        'type' => 'button_group',
                                        'display' => __('Script Type'),
                                        'width' => 50,
                                    ],
                                ],
                                [
                                    'handle' => 'spacer',
                                    'field' => ['type' => 'spacer', 'width' => 50],
                                ],
                                [
                                    'handle' => 'gtm_container_id',
                                    'field' => [
                                        'type' => 'text',
                                        'display' => __('Container ID'),
                                        'instructions' => __('You can find this at the top right of your Google Tag Manager account. Usually starts with `GTM-`.'),
                                        'if' => ['script_type' => 'equals google-tag-manager'],
                                    ],
                                ],
                                [
                                    'handle' => 'meta_pixel_id',
                                    'field' => [
                                        'type' => 'text',
                                        'display' => __('Pixel ID'),
                                        'instructions' => __('You can find this in your Meta Events Manager account.'),
                                        'if' => ['script_type' => 'equals meta-pixel'],
                                    ],
                                ],
                                [
                                    'handle' => 'inline_javascript',
                                    'field' => [
                                        'type' => 'code',
                                        'display' => __('Inline JavaScript'),
                                        'instructions' => __('Please remove the `<script>` and `</script>` tags.'),
                                        'mode' => 'javascript',
                                        'mode_selectable' => false,
                                        'if' => ['script_type' => 'equals other'],
                                    ],
                                ]
                            ],
                            'mode' => 'stacked',
                            'type' => 'grid',
                            'display' => 'Scripts',
                            'instructions' => 'Configure the scripts you want to include in this consent group.',
                            'add_row' => 'Add Script',
                            'fullscreen' => false,
                        ],
                    ]
                ];

                return [$consentGroup['handle'] => ['display' => $consentGroup['name'], 'sections' => [['fields' => $fields]]]];
            })->all(),
        ]);
    }
}
