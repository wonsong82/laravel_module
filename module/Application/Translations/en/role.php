<?php
return [
    'name' => 'Role',
    'name_plural' => 'Roles',
    'field' => [
        'name' => 'Name',
        'permissions' => 'Permissions',
    ],
    'tab' => [
        'main' => 'General'
    ],
    'log' => [
        'created' => [
            'title' => 'Role added',
            'text' => 'Role ":name" added.'
        ],
        'deleted' => [
            'title' => 'Role deleted',
            'text' => 'Role ":name" deleted.'
        ],
        'updated' => [
            'title' => 'Role changed',
            'text' => 'Role ":name" changed.',
            'detail' => ':field: ":from" => ":to"',
            'permission' => [
                'created' => 'Permission ":name" added.',
                'deleted' => 'Permission ":name" removed.'
            ]
        ]
    ],
];