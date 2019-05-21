<?php
return [
    'name' => 'UOM',
    'name_plural' => 'Units',
    'field' => [
        'code' => 'Code',
        'isc' => 'Int. Standard Code',
        'desc' => 'Description'
    ],
    'tab' => [
        'main' => 'General'
    ],
    'log' => [
        'created' => [
            'title' => 'Payterm added',
            'text' => 'Payterm ":name" added.'
        ],
        'deleted' => [
            'title' => 'Payterm deleted',
            'text' => 'Payterm ":name" deleted.'
        ],
        'updated' => [
            'title' => 'Payterm changed',
            'text' => 'Payterm ":name" changed.',
            'detail' => ':field: ":from" to ":to"'
        ]
    ]
];