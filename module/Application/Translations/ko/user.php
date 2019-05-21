<?php
return [
    'name' => '유저',
    'name_plural' => '유저',
    'field' => [
        'status' => '상태',
        'status_code' => '상태',
        'email' => '이메일',
        'name' => '이름',
        'password' => '비밀번호',
        'password_confirmation' => '비밀번호 확인',
        'timezone' => '시간대',
        'locale' => '로케일',
        'locale_id' => '언어',
        'language' => '언어',
        'roles' => '역할',
        'permissions' => '권한',
        'extra_permissions' => '추가권한',
        'roles_and_permissions' => '역할 및 권한',
    ],
    'tab' => [
        'main' => '기본정보',
        'role' => '역할',
    ],
    'log' => [
        'created' => [
            'title' => '유저등록',
            'text' => '":name" 유저가 등록되었습니다.'
        ],
        'deleted' => [
            'title' => '유저삭제',
            'text' => '":name" 유저가 삭제되었습니다.'
        ],
        'updated' => [
            'title' => '유저수정',
            'text' => '":name" 유저가 수정되었습니다.',
            'detail' => ':field: ":from" => ":to"',
            'roles' => [
                'created' => '":name" 역할이 추가되었습니다.',
                'deleted' => '":name" 역할이 삭제되었습니다.',
            ],
            'permissions' => [
                'created' => '":name" 권한이 추가되었습니다.',
                'deleted' => '":name" 권한이 삭제되었습니다.',
            ]
        ]
    ]
];