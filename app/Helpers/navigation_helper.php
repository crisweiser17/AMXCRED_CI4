<?php

if (!function_exists('get_navigation')) {
    /**
     * Get navigation configuration
     * 
     * @return array
     */
    function get_navigation(): array
    {
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
    }
}

if (!function_exists('is_active_route')) {
    /**
     * Check if current route is active
     * 
     * @param string $route
     * @return bool
     */
    function is_active_route(string $route): bool
    {
        $currentUrl = current_url();
        return strpos($currentUrl, $route) !== false;
    }
}

if (!function_exists('get_user_menu')) {
    /**
     * Get user menu items
     * 
     * @return array
     */
    function get_user_menu(): array
    {
        $navigation = get_navigation();
        return $navigation['user_menu'] ?? [];
    }
}

if (!function_exists('get_modules')) {
    /**
     * Get all modules
     * 
     * @return array
     */
    function get_modules(): array
    {
        $navigation = get_navigation();
        return $navigation['modules'] ?? [];
    }
}

if (!function_exists('get_module')) {
    /**
     * Get specific module
     * 
     * @param string $moduleKey
     * @return array|null
     */
    function get_module(string $moduleKey): ?array
    {
        $modules = get_modules();
        return $modules[$moduleKey] ?? null;
    }
}