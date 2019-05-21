<?php
return [
    'name' => 'Permission',
    'name_plural' => 'Permissions',
    'field' => [
        'name' => 'Name',
    ],
    'tab' => [
        'main' => 'General'
    ],
    'log' => [
        'created' => [
            'title' => 'Permission added',
            'text' => 'Permission ":name" added.'
        ],
        'deleted' => [
            'title' => 'Permission deleted',
            'text' => 'Permission ":name" deleted.'
        ],
        'updated' => [
            'title' => 'Permission changed',
            'text' => 'Permission ":name" changed.',
            'detail' => ':field: ":from" => ":to"'
        ]
    ],
];