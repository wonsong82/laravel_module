<?php
return [
    'name' => '통화코드',
    'name_plural' => '통화코드',
    'field' => [
        'code' => '코드',
        'name' => '이름',
        'code_n' => '숫자코드',
        'symbol' => '기호',
        'symbol_position' => '기호위치',
        'decimal_count' => '소수점',
        'decimal_separator' => '소수점기호',
        'thousand_separator' => '천자리기호'
    ],
    'tab' => [
        'main' => '기본'
    ],
    'log' => [
        'created' => [
            'title' => '통화등록',
            'text' => '":name" 통화가 등록되었습니다.'
        ],
        'deleted' => [
            'title' => '통화삭제',
            'text' => '":name" 통화가 삭제되었습니다.'
        ],
        'updated' => [
            'title' => '통화수정',
            'text' => '":name" 통화가 수정되었습니다.',
            'detail' => ':field: ":from" => ":to"'
        ]
    ],
    'default' => [
        'us_dollar' => '미국 달러',
        'korean_won' => '한국 원',
        'euro' => '유로',
        'china_yuan' => '중국 유엔'
    ]
];