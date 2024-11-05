<?php

return [
    'module' => [
        [
            'title' => 'QL Nhóm Thành Viên',
            'icon' => 'fa fa-user',
            'name' => ['user', 'permission'],
            'subModule' => [
                [
                    'title' => 'QL Nhóm Thành Viên',
                    'route' => 'user/catalogue/index',
                ],
                [
                    'title' => 'QL Thành Viên',
                    'route' => 'user/index',
                ],
                [
                    'title' => 'QL Quyền',
                    'route' => 'permission/index',
                ],
            ],
        ],
        [
            'title' => 'QL Bài Viết',
            'icon' => 'fa fa-clipboard',
            'name' => ['post'],
            'subModule' => [
                [
                    'title' => 'QL Nhóm Bài Viết',
                    'route' => 'post/catalogue/index',
                ],
                [
                    'title' => 'QL Bài Viết',
                    'route' => 'post/index',
                ],
            ],
        ],
        [
            'title' => 'Cấu hình chung',
            'icon' => 'fa fa-cog',
            'name' => ['language', 'generate'],
            'subModule' => [
                [
                    'title' => 'QL Ngôn Ngữ',
                    'route' => 'language/index',
                ],
                [
                    'title' => 'QL Module',
                    'route' => 'generate/index',
                ],
            ],
        ],
        
    ],
];
