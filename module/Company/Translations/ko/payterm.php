<?php
return [
    'name' => '납부기간',
    'name_plural' => '납부기간',
    'field' => [
        'code' => '코드',
        'name' => '이름',
        'desc' => '설명'
    ],
    'tab' => [
        'main' => '기본'
    ],
    'log' => [
        'created' => [
            'title' => '납부기간등록',
            'text' => '":name" 납부기간이 등록되었습니다.'
        ],
        'deleted' => [
            'title' => '납부기간삭제',
            'text' => '":name" 납부기간이 삭제되었습니다.'
        ],
        'updated' => [
            'title' => '납부기간수정',
            'text' => '":name" 납부기간이 수정되었습니다.',
            'detail' => ':field: ":from" => ":to"'
        ]
    ]
];