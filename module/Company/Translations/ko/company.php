<?php
return [
    'name' => '회사',
    'name_plural' => '회사',
    'field' => [
        'code' => '코드',
        'status' => '상태',
        'status_code' => '상태',
        'name' => '이름',
        'legal_name' => '법인명',
        'desc' => '설명',
        'phone' => '전화번호',
        'fax' => '팩스',
        'email' => '이메일',
        'website' => '웹사이트',
        'address' => '주소',
        'currency' => '통화',
        'currency_id' => '통화',
        'currency_code' => '통화',
        'locale' => '언어',
        'locale_id' => '언어',
        'timezone' => '표준시간대',
        'note' => '메모',
        'physical' => '회사주소',
        'shipping' => '배송주소',
        'billing' => '청구주소'
    ],
    'tab' => [
        'main' => '기본정보',
        'physical_address' => '회사주소',
        'shipping_address' => '배송주소',
        'billing_address' => '청구주소'
    ],
    'log' => [
        'created' => [
            'title' => '회사등록',
            'text' => '":name" 회사가 등록되었습니다.'
        ],
        'deleted' => [
            'title' => '회사삭제',
            'text' => '":name" 회사가 삭제되었습니다.'
        ],
        'updated' => [
            'title' => '회사수정',
            'text' => '":name" 회사가 수정되었습니다.',
            'detail' => ':field: ":from" => ":to"'
        ]
    ]
];