<?php
return [
    'name' => '측정단위',
    'name_plural' => '측정단위',
    'field' => [
        'code' => '코드',
        'isc' => '국제표준코드',
        'desc' => '설명'
    ],
    'tab' => [
        'main' => '기본'
    ],
    'log' => [
        'created' => [
            'title' => '측정단위등록',
            'text' => '":name" 측정단위가 등록되었습니다.'
        ],
        'deleted' => [
            'title' => '측정단위삭제',
            'text' => '":name" 측정단위가 삭제되었습니다.'
        ],
        'updated' => [
            'title' => '측정단위수정',
            'text' => '":name" 측정단위가 수정되었습니다.',
            'detail' => ':field: ":from" => ":to"'
        ]
    ]
];