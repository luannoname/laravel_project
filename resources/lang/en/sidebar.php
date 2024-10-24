<?php

return [
    'module' => [
        [
            'title' => 'Member Management',
            'icon' => 'fa fa-dashboard',
            'name' => ['user'],
            'subModule' => [
                [
                    'title' => 'Member Group',
                    'route' => 'user/catalogue/index',
                ],
                [
                    'title' => 'Member',
                    'route' => 'user/index',
                ],
            ],
        ],
        [
            'title' => 'Post Management',
            'icon' => 'fa fa-clipboard',
            'name' => ['post'],
            'subModule' => [
                [
                    'title' => 'Post Group',
                    'route' => 'post/catalogue/index',
                ],
                [
                    'title' => 'Post',
                    'route' => 'post/index',
                ],
            ],
        ],
        [
            'title' => 'General Settings',
            'icon' => 'fa fa-cog',
            'name' => ['language'],
            'subModule' => [
                [
                    'title' => 'Language',
                    'route' => 'language/index',
                ]
            ],
        ],
        
    ],
];
