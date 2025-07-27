<?php

// Script para criar o cliente João Ok aprovado e elegível para empréstimos

require_once 'vendor/autoload.php';

// Configurar o ambiente CodeIgniter
$app = \Config\Services::codeigniter();
$app->initialize();

use App\Models\ClientModel;
use App\Models\CpfConsultationModel;

$clientModel = new ClientModel();
$cpfConsultationModel = new CpfConsultationModel();

// Dados do cliente João Ok
$clientData = [
    'full_name' => 'João Ok',
    'cpf' => '123.456.789-00',
    'email' => 'joao.ok@email.com',
    'phone' => '(11) 99999-9999',
    'birth_date' => '1990-01-01',
    'address' => 'Rua das Flores, 123',
    'neighborhood' => 'Centro',
    'city' => 'São Paulo',
    'state' => 'SP',
    'zip_code' => '01234-567',
    'monthly_income' => 5000.00,
    'profession' => 'Desenvolvedor',
    'company' => 'Tech Company',
    'work_phone' => '(11) 3333-3333',
    'emergency_contact_name' => 'Maria Ok',
    'emergency_contact_phone' => '(11) 88888-8888',
    'emergency_contact_relationship' => 'Esposa',
    // Documentos fictícios (simulando que foram enviados)
    'id_front' => 'joao_ok_rg_frente.jpg',
    'id_back' => 'joao_ok_rg_verso.jpg',
    'selfie' => 'joao_ok_selfie.jpg',
    'created_at' => date('Y-m-d H:i:s'),
    'updated_at' => date('Y-m-d H:i:s')
];

try {
    // Inserir o cliente
    $clientId = $clientModel->insert($clientData);
    
    if (!$clientId) {
        throw new Exception('Erro ao criar cliente: ' . implode(', ', $clientModel->errors()));
    }
    
    echo "Cliente João Ok criado com ID: {$clientId}\n";
    
    // Criar consulta CPF aprovada
    $cpfData = [
        'client_id' => $clientId,
        'raw_json' => json_encode([
            'status' => 1,
            'cpf' => '12345678900',
            'cpf_valido' => true,
            'cpf_regular' => true,
            'nome' => 'JOAO OK',
            'nascimento' => '01/01/1990',
            'situacao' => 'REGULAR',
            'dados_divergentes' => false,
            'obito' => false
        ]),
        'cpf_valido' => true,
        'cpf_regular' => true,
        'dados_divergentes' => false,
        'obito' => false,
        'status' => 'aprovado',
        'motivo_reprovacao' => null,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ];
    
    $consultationId = $cpfConsultationModel->insert($cpfData);
    
    if (!$consultationId) {
        throw new Exception('Erro ao criar consulta CPF: ' . implode(', ', $cpfConsultationModel->errors()));
    }
    
    echo "Consulta CPF aprovada criada com ID: {$consultationId}\n";
    
    // Definir verificação visual como aprovada na sessão
    session_start();
    $_SESSION["visual_verification_{$clientId}"] = 'aprovado';
    
    echo "Verificação visual definida como aprovada na sessão\n";
    
    // Verificar elegibilidade
    $isEligible = $clientModel->isClientEligible($clientId);
    
    echo "\n=== RESUMO ===\n";
    echo "Cliente: {$clientData['full_name']}\n";
    echo "ID: {$clientId}\n";
    echo "CPF: {$clientData['cpf']}\n";
    echo "Email: {$clientData['email']}\n";
    echo "Documentos: ✓ RG Frente, ✓ RG Verso, ✓ Selfie\n";
    echo "Verificação Visual: ✓ Aprovada\n";
    echo "Consulta CPF: ✓ Aprovada\n";
    echo "Elegível para empréstimos: " . ($isEligible ? '✓ SIM' : '✗ NÃO') . "\n";
    
    if ($isEligible) {
        echo "\n🎉 Cliente João Ok criado com sucesso e está elegível para empréstimos!\n";
        echo "Acesse: http://localhost:8081/clients/verify/{$clientId} para visualizar\n";
    } else {
        echo "\n⚠️ Algo deu errado. Cliente não está elegível.\n";
    }
    
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
    exit(1);
}