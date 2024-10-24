<?php

return [
    'module' => [
        [
            'title' => 'QL Thành Viên',
            'icon' => 'fa fa-dashboard',
            'name' => ['user'],
            'subModule' => [
                [
                    'title' => 'QL Nhóm Thành Viên',
                    'route' => 'user/catalogue/index',
                ],
                [
                    'title' => 'QL Thành Viên',
                    'route' => 'user/index',
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
            'name' => ['language'],
            'subModule' => [
                [
                    'title' => 'QL Ngôn Ngữ',
                    'route' => 'language/index',
                ]
            ],
        ],
        
    ],
];
