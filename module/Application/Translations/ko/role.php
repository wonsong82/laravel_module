<?php
return [
    'name' => '역할',
    'name_plural' => '역할',
    'field' => [
        'name' => '이름',
        'permissions' => '권한'
    ],
    'tab' => [
        'main' => '기본정보'
    ],
    'log' => [
        'created' => [
            'title' => '역할등록',
            'text' => '":name" 역할이 등록되었습니다.'
        ],
        'deleted' => [
            'title' => '역할삭제',
            'text' => '":name" 역할이 삭제되었습니다.'
        ],
        'updated' => [
            'title' => '역할수정',
            'text' => '":name" 역할이 수정되었습니다.',
            'detail' => ':field: ":from" => ":to"',
            'permission' => [
                'created' => '":name" 권한이 추가되었습니다.',
                'deleted' => '":name" 권한이 삭제되었습니다.'
            ]
        ]
    ],
];