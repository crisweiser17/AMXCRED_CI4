<?php

// Definir FCPATH
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR);

// Carregar o CodeIgniter
require_once __DIR__ . '/app/Config/Paths.php';
$paths = new Config\Paths();
require_once $paths->systemDirectory . '/bootstrap.php';

// Inicializar o CodeIgniter
$app = Config\Services::codeigniter();
$app->initialize();

// Conectar ao banco de dados
$db = \Config\Database::connect();

// Atualizar cliente 1 com documentos visuais
$result = $db->table('clients')
    ->where('id', 1)
    ->update([
        'id_front' => 'id_front.jpg',
        'id_back' => 'id_back.jpg', 
        'selfie' => 'selfie.jpg'
    ]);

if ($result) {
    echo "Cliente 1 atualizado com documentos visuais\n";
} else {
    echo "Erro ao atualizar cliente 1\n";
}

// Inserir consulta de CPF aprovada para cliente 1
$cpfData = [
    'client_id' => 1,
    'response_data' => json_encode([
        'status' => 1,
        'cpf' => '123.456.789-01',
        'nome' => 'João Silva',
        'situacao' => 'regular'
    ]),
    'is_valid' => 1,
    'has_restrictions' => 0,
    'has_pending_issues' => 0,
    'risk_score' => 1,
    'status' => 'aprovado',
    'observations' => 'CPF regular e aprovado',
    'created_at' => date('Y-m-d H:i:s'),
    'updated_at' => date('Y-m-d H:i:s')
];

$cpfResult = $db->table('cpf_consultation')->insert($cpfData);

if ($cpfResult) {
    echo "Consulta de CPF aprovada criada para cliente 1\n";
} else {
    echo "Erro ao criar consulta de CPF\n";
}

echo "Cliente 1 agora está elegível para empréstimos!\n";