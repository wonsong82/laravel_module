<?php
return [
    'name' => 'Unit',
    'name_plural' => 'Units',
    'field' => [
        'status' => 'Status',
        'status_code' => 'Status',
        'type' => 'Type',
        'type_code' => 'Type',
        'symbol' => 'Symbol',
        'name' => 'Name',
        'plural_name' => 'Plural Name',
        'desc' => 'Description',
        'activate' => 'Activate',
        'deactivate' => 'Deactivate'
    ],
    'tab' => [
        'main' => 'General'
    ],
    'log' => [
        'created' => [
            'title' => 'Unit added',
            'text' => 'Unit ":name" added.'
        ],
        'deleted' => [
            'title' => 'Unit deleted',
            'text' => 'Unit ":name" deleted.'
        ],
        'updated' => [
            'title' => 'Unit changed',
            'text' => 'Unit ":name" changed.',
            'detail' => ':field: ":from" => ":to"'
        ]
    ]
];