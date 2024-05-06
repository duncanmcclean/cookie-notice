<?php

namespace DuncanMcClean\CookieNotice\Scripts;

use Statamic\Fields\Blueprint as StatamicBlueprint;

class Blueprint
{
    public static function blueprint(): StatamicBlueprint
    {
        return app(StatamicBlueprint::class)->setContents([
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