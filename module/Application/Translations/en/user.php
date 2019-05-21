<?php
return [
    'name' => 'User',
    'name_plural' => 'Users',
    'field' => [
        'status' => 'Status',
        'status_code' => 'Status',
        'email' => 'Email',
        'name' => 'Name',
        'password' => 'Password',
        'password_confirmation' => 'Password Confirmation',
        'timezone' => 'Timezone',
        'locale' => 'Locale',
        'locale_id' => 'Language',
        'language' => 'Language',
        'roles' => 'Roles',
        'permissions' => 'Permissions',
        'extra_permissions' => 'Extra Permissions',
        'roles_and_permissions' => 'Roles and Permissions',
    ],
    'tab' => [
        'main' => 'General',
        'role' => 'Roles',
    ],
    'log' => [
        'created' => [
            'title' => 'User added',
            'text' => 'User ":name" added.'
        ],
        'deleted' => [
            'title' => 'User deleted',
            'text' => 'User ":name" deleted.'
        ],
        'updated' => [
            'title' => 'User changed',
            'text' => 'User ":name" changed.',
            'detail' => ':field: ":from" => ":to"',
            'roles' => [
                'created' => 'Role ":name" added.',
                'deleted' => 'Role ":name" removed.',
            ],
            'permissions' => [
                'created' => 'Extra permission ":name" added.',
                'deleted' => 'Extra permission ":name" removed.',
            ]
        ]
    ],
];