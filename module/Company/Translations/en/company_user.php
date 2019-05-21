<?php
return [
    'name' => 'User',
    'name_plural' => 'Users',
    'field' => [
        'company' => 'Company',
        'status' => 'Status',
        'status_code' => 'Status',
        'code' => 'Code',
        'code_hint' => 'Leave it blank to auto assign code',
        'name' => 'Name',
        'email' => 'Login Email',
        'password' => 'Password',
        'password_confirmation' => 'Password Confirmation',
        'roles' => 'Roles',
        'permissions' => 'Permissions',
        'extra_permissions' => 'Extra Permissions',
        'roles_and_permissions' => 'Roles and Permissions',
        'timezone' => 'Timezone',
        'locale' => 'Language'
    ],
    'tab' => [
        'main' => 'General',
        'role' => 'Roles',
    ],
    'show' => [
        'user' => 'User Info',
        'roles_permissions' => 'Roles & Permissions',
        'roles' => 'Roles',
        'permissions' => 'Permissions'

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
    ]
];