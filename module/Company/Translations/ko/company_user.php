<?php
return [
    'name' => '유저',
    'name_plural' => '유저',
    'field' => [
        'company' => '회사',
        'status' => '상태',
        'status_code' => '상태',
        'code' => '코드',
        'code_hint' => '입력하지 않을시 코드 자동생성',
        'name' => '이름',
        'email' => '로그인 이메일',
        'password' => '비밀번호',
        'password_confirmation' => '비밀번호 확인',
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