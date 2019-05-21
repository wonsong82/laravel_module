<?php
return [
    'name' => 'Payterm',
    'name_plural' => 'Payterms',
    'field' => [
        'status' => 'Status',
        'status_code' => 'Status',
        'code' => 'Code',
        'name' => 'Name',
        'desc' => 'Description',
        'activate' => 'Activate',
        'deactivate' => 'Deactivate'
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
            'detail' => ':field: ":from" => ":to"'
        ]
    ]
];