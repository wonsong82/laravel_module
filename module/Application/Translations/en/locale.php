<?php
return [
    'name' => 'Locale',
    'name_plural' => 'Locales',
    'field' => [
        'code' => 'Code',
        'country_code' => 'Country Code',
        'language_code' => 'Language Code',
        'country_name' => 'Country Name',
        'language_name' => 'Language Name'
    ],
    'tab' => [
        'main' => 'General',
    ],
    'log' => [
        'created' => [
            'title' => 'Locale added',
            'text' => 'Locale ":name" added.'
        ],
        'deleted' => [
            'title' => 'Locale deleted',
            'text' => 'Locale ":name" deleted.'
        ],
        'updated' => [
            'title' => 'Locale changed',
            'text' => 'Locale ":name" changed.',
            'detail' => ':field: ":from" => ":to"',
        ]
    ]
];