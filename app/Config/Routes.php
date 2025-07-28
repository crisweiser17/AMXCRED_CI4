<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::dashboard');
$routes->get('/dashboard', 'AuthController::dashboard');

// Rotas de Autenticação
$routes->group('admin', function($routes) {
    $routes->get('login', 'AuthController::login');
    $routes->post('login', 'AuthController::processLogin');
    $routes->get('logout', 'AuthController::logout');
});

// Rotas de Clientes
$routes->get('/clients', 'ClientController::index');
$routes->get('/clients/create', 'ClientController::create');
$routes->post('/clients/store', 'ClientController::store');
$routes->get('/clients/view/(:num)', 'ClientController::view/$1');
$routes->get('/clients/edit/(:num)', 'ClientController::edit/$1');
$routes->put('/clients/update/(:num)', 'ClientController::update/$1');
$routes->post('/clients/update/(:num)', 'ClientController::update/$1');
$routes->delete('/clients/delete/(:num)', 'ClientController::delete/$1');
$routes->get('/clients/verify/(:num)', 'ClientController::verify/$1');
$routes->post('/clients/verify/visual/(:num)', 'ClientController::verifyVisual/$1');
$routes->post('/clients/verify/cpf/(:num)', 'ClientController::verifyCpf/$1');
$routes->post('/clients/verify/risk/(:num)', 'ClientController::verifyRisk/$1');
$routes->post('/clients/update-from-api/(:num)', 'ClientController::updateFromApi/$1');

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
    $routes->get('cpf-api', 'SettingsController::cpfApi');
    $routes->post('save-cpf-api', 'SettingsController::saveCpfApi');
    $routes->post('test-cpf-api', 'SettingsController::testCpfApi');
    $routes->get('smtp', 'SettingsController::smtp');
    $routes->post('save-smtp', 'SettingsController::saveSmtp');
    $routes->post('test-smtp', 'SettingsController::testSmtp');
    $routes->get('colors', 'SettingsController::colors');
    $routes->get('payment', 'SettingsController::payment');
    $routes->get('timezone', 'SettingsController::timezone');
    $routes->post('save-timezone', 'SettingsController::saveTimezone');
    
    // Rotas para Planos de Empréstimo
    $routes->get('loan-plans', 'SettingsController::loanPlans');
    $routes->get('loan-plans/create', 'SettingsController::createLoanPlan');
    $routes->post('loan-plans/store', 'SettingsController::storeLoanPlan');
    $routes->get('loan-plans/view/(:num)', 'SettingsController::viewLoanPlan/$1');
    $routes->get('loan-plans/edit/(:num)', 'SettingsController::editLoanPlan/$1');
    $routes->post('loan-plans/update/(:num)', 'SettingsController::updateLoanPlan/$1');
    $routes->put('loan-plans/update/(:num)', 'SettingsController::updateLoanPlan/$1');
    $routes->post('loan-plans/toggle-status/(:num)', 'SettingsController::toggleLoanPlanStatus/$1');
    $routes->delete('loan-plans/delete/(:num)', 'SettingsController::deleteLoanPlan/$1');
    
    // Rotas para Mensagens do Sistema
    $routes->get('system-messages', 'SettingsController::systemMessages');
    $routes->post('save-system-messages', 'SettingsController::saveSystemMessages');
    
    // Rotas para Informações da Empresa
    $routes->get('company-info', 'SettingsController::companyInfo');
    $routes->post('save-company-info', 'SettingsController::saveCompanyInfo');
    
    // Rota de debug temporária
    $routes->get('loan-plans/debug/(:num)', 'SettingsControllerDebug::viewLoanPlanDebug/$1');
    
    // Rotas da Área de Testes
    $routes->get('test-area', 'TestAreaController::index');
    $routes->get('test-area/test', 'TestAreaController::test');
    $routes->get('test-area/debug', 'TestAreaController::debug');
    $routes->post('test-area/update-loan-status', 'TestAreaController::updateLoanStatus');
    $routes->post('test-area/delete-loan', 'TestAreaController::deleteLoan');
    $routes->get('test-area/get-loan-installments/(:num)', 'TestAreaController::getLoanInstallments/$1');
    $routes->post('test-area/mark-installment-paid', 'TestAreaController::markInstallmentAsPaid');
});

// Rotas de Empréstimos
$routes->group('loans', function($routes) {
    $routes->get('/', 'LoansController::index');
    $routes->get('create', 'LoansController::create');
    $routes->post('store', 'LoansController::store');
    $routes->get('view/(:num)', 'LoansController::view/$1');
    $routes->post('fund/(:num)', 'LoansController::fund/$1');
    $routes->post('cancel/(:num)', 'LoansController::cancel/$1');
    $routes->post('send-notification/(:num)', 'LoansController::sendNotification/$1');
});

// Rotas Públicas de Aceitação de Empréstimo (sem autenticação)
$routes->get('/accept-loan/(:segment)', 'LoansController::accept/$1');
$routes->get('/loans/accept/(:segment)', 'LoansController::accept/$1'); // Adicionado para compatibilidade com links gerados
$routes->post('/loans/process-acceptance', 'LoansController::processAcceptance');
$routes->get('/loan-acceptance-success', 'LoansController::acceptanceSuccess');
$routes->get('/loans/acceptance-success', 'LoansController::acceptanceSuccess'); // Adicionado para compatibilidade
$routes->get('/loans/rejection-success', 'LoansController::rejectionSuccess'); // Página específica para recusa
$routes->get('/loan-acceptance-error', 'LoansController::acceptanceError');
$routes->get('/loans/acceptance-error', 'LoansController::acceptanceError'); // Adicionado para compatibilidade com redirecionamentos
$routes->get('/loans/cancel/(:num)', 'LoansController::cancel/$1'); // Adicionado para compatibilidade com cancelamento

// Rotas de Debug para Planos de Empréstimo
$routes->get('/loan-plans-debug', 'LoanPlansController::index');
$routes->get('/loan-plans-debug/view/(:num)', 'LoanPlansController::view/$1');

// Rotas de Teste Absoluto (HTML estático)
$routes->get('/loan-simple', 'LoanPlansSimple::index');
$routes->get('/loan-simple/view/(:num)', 'LoanPlansSimple::view/$1');

// Rotas Públicas (sem autenticação)
$routes->get('/register', 'PublicController::register');
$routes->post('/register', 'PublicController::store');
$routes->post('/register/store', 'PublicController::store');
$routes->get('/register/success', 'PublicController::success');
