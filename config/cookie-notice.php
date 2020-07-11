<?php

return [

    // Name of the cookie used to store the users' prefrences
    'cookie_name' => 'COOKIE_NOTICE',

    // Consent groups
    'groups' => [
        'Necessary' => [
            'required' => true,
            'toggle_by_default' => true,
        ],
        'Statistics' => [
            'required' => false,
            'toggle_by_default' => false,
        ],
        'Marketing' => [
            'required' => false,
            'toggle_by_default' => false,
        ],
    ],

];