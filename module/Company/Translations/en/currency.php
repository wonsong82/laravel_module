<?php
return [
    'name' => 'Currency',
    'name_plural' => 'Currencies',
    'field' => [
        'status' => 'Status',
        'status_code' => 'Status',
        'code' => 'Code',
        'name' => 'Name',
        'code_n' => 'Code Numeric',
        'symbol' => 'Symbol',
        'symbol_position' => 'Symbol Position',
        'decimal_count' => 'Decimal Count',
        'decimal_separator' => 'Decimal Separator',
        'thousand_separator' => 'Thousand Separator',
        'activate' => 'Activate',
        'deactivate' => 'Deactivate'
    ],
    'tab' => [
        'main' => 'General'
    ],
    'log' => [
        'created' => [
            'title' => 'Currency added',
            'text' => 'Currency ":name" added.'
        ],
        'deleted' => [
            'title' => 'Currency deleted',
            'text' => 'Currency ":name" deleted.'
        ],
        'updated' => [
            'title' => 'Currency changed',
            'text' => 'Currency ":name" changed.',
            'detail' => ':field: ":from" => ":to"'
        ]
    ],
    'default' => [
        'us_dollar' => 'US dollar',
        'korean_won' => 'Korean won',
        'euro' => 'Euro',
        'china_yuan' => 'China yuan'
    ]
];