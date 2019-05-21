<?php
return [
    'name' => 'Company',
    'name_plural' => 'Companies',
    'field' => [
        'code' => 'Code',
        'status' => 'Status',
        'status_code' => 'Status',
        'name' => 'Name',
        'legal_name' => 'Legal Name',
        'desc' => 'Description',
        'phone' => 'Phone #',
        'fax' => 'Fax #',
        'email' => 'Email',
        'website' => 'Website',
        'address' => 'Address',
        'currency' => 'Currency',
        'currency_id' => 'Currency',
        'currency_code' => 'Currency',
        'locale' => 'Language',
        'locale_id' => 'Locale',
        'timezone' => 'Timezone',
        'note' => 'Remark',
        'physical' => 'Company Address',
        'shipping' => 'Shipping Address',
        'billing' => 'Billing Address'
    ],
    'tab' => [
        'main' => 'General',
        'physical_address' => 'Address',
        'shipping_address' => 'Shipping',
        'billing_address' => 'Billing'
    ],
    'show' => [
        'company_info' => 'Company Info',
        'address' => 'Address',
        'additional_info' => 'Additional Info',
    ],
    'log' => [
        'created' => [
            'title' => 'Company added',
            'text' => 'Company ":name" added.'
        ],
        'deleted' => [
            'title' => 'Company deleted',
            'text' => 'Company ":name" deleted.'
        ],
        'updated' => [
            'title' => 'Company changed',
            'text' => 'Company ":name" changed.',
            'detail' => ':field: ":from" => ":to"'
        ]
    ],
];