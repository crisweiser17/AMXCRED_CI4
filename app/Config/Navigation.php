<?php

return [
    'modules' => [
        'dashboard' => [
            'name' => 'Dashboard',
            'icon' => 'heroicons-home',
            'url' => '/',
            'items' => []
        ],
        'clients' => [
            'name' => 'Clientes',
            'icon' => 'heroicons-user-group',
            'items' => [
                'list' => [
                    'name' => 'Listar Clientes',
                    'url' => '/clients',
                    'icon' => 'heroicons-list-bullet'
                ],
                'create' => [
                    'name' => 'Novo Cliente',
                    'url' => '/clients/create',
                    'icon' => 'heroicons-plus-circle'
                ]
            ]
        ]
    ],
    
    'user_menu' => [
        'profile' => [
            'name' => 'Meu Perfil',
            'url' => '/profile',
            'icon' => 'heroicons-user-circle'
        ],
        'settings' => [
            'name' => 'Configurações',
            'url' => '/settings',
            'icon' => 'heroicons-cog-6-tooth'
        ],
        'logout' => [
            'name' => 'Sair',
            'url' => '/logout',
            'icon' => 'heroicons-arrow-left-on-rectangle'
        ]
    ]
];