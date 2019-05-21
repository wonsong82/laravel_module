<?php
return [
    'name' => '권한',
    'name_plural' => '권한',
    'field' => [
        'name' => '이름',
    ],
    'tab' => [
        'main' => '기본정보'
    ],
    'log' => [
        'created' => [
            'title' => '권한등록',
            'text' => '":name" 권한이 등록되었습니다.'
        ],
        'deleted' => [
            'title' => '권한삭제',
            'text' => '":name" 권한이 삭제되었습니다.'
        ],
        'updated' => [
            'title' => '권한수정',
            'text' => '":name" 권한이 수정되었습니다.',
            'detail' => ':field: ":from" => ":to"'
        ]
    ],
];