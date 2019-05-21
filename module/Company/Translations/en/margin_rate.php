<?php
return [
    'name' => 'Margin Rate',
    'name_plural' => 'Margin Rates',
    'field' => [
        'rates' => 'Rates',
        'rate' => 'Rate'
    ],
    'tab' => [
        'main' => 'General',
    ],
    'show' => [
        'rate_table' => 'Rates Table',
    ],
    'log' => [
        'created' => [
            'title' => 'Margin Rate added',
            'text' => 'Margin Rate ":name" added.'
        ],
        'deleted' => [
            'title' => 'Margin Rate deleted',
            'text' => 'Margin Rate ":name" deleted.'
        ],
        'updated' => [
            'title' => 'Margin Rate changed',
            'text' => 'Margin Rate changed.',
            'detail' => ':field: ":from" => ":to"',
//            'has_many' => [
//                'created' => 'relation ":name" added.',
//                'deleted' => 'relation ":name" removed.',
//                'updated' => 'relation :field: ":from" => ":to"'
//            ]
        ]
    ]
];