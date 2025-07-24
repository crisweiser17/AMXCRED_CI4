<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/dashboard', 'Home::dashboard');

// Rotas de Clientes
$routes->get('/clients', 'ClientController::index');
$routes->get('/clients/create', 'ClientController::create');
$routes->post('/clients/store', 'ClientController::store');
$routes->get('/clients/edit/(:num)', 'ClientController::edit/$1');
$routes->put('/clients/update/(:num)', 'ClientController::update/$1');
$routes->post('/clients/update/(:num)', 'ClientController::update/$1');
$routes->delete('/clients/delete/(:num)', 'ClientController::delete/$1');

// Rotas de Documentos
$routes->group('documents', function($routes) {
    $routes->get('serve/(:num)/([a-zA-Z0-9_]+)', 'DocumentController::serve/$1/$2');
    $routes->get('thumbnail/(:num)/([a-zA-Z0-9_]+)', 'DocumentController::thumbnail/$1/$2');
    $routes->get('info/(:num)/([a-zA-Z0-9_]+)', 'DocumentController::info/$1/$2');
    $routes->delete('delete/(:num)/([a-zA-Z0-9_]+)', 'DocumentController::delete/$1/$2');
});

// Rotas de Configurações
$routes->group('settings', function($routes) {
    $routes->get('/', 'SettingsController::index');
    $routes->get('required-fields', 'SettingsController::requiredFields');
    $routes->post('update-required-fields', 'SettingsController::updateRequiredFields');
    $routes->get('get-setting', 'SettingsController::getSetting');
    $routes->post('set-setting', 'SettingsController::setSetting');
    $routes->get('smtp', 'SettingsController::smtp');
    $routes->get('colors', 'SettingsController::colors');
    $routes->get('payment', 'SettingsController::payment');
});
