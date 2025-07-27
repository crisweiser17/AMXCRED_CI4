<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\ClientModel;

class ApproveJoaoVisual extends BaseCommand
{
    protected $group       = 'Custom';
    protected $name        = 'approve:joao-visual';
    protected $description = 'Aprova a verificação visual do cliente João Ok';

    public function run(array $params)
    {
        $clientModel = new ClientModel();
        
        // Buscar o cliente João Ok
        $client = $clientModel->where('full_name', 'João Ok')->first();
        
        if (!$client) {
            CLI::error('Cliente João Ok não encontrado!');
            return;
        }
        
        $clientId = $client['id'];
        
        CLI::write("Cliente encontrado: {$client['full_name']} (ID: {$clientId})", 'green');
        
        // Simular aprovação da verificação visual na sessão
        // Como estamos em CLI, vamos usar uma abordagem diferente
        // Vamos verificar se podemos modificar o sistema para não depender apenas da sessão
        
        CLI::write('Verificando status atual...', 'yellow');
        
        // Verificar se tem documentos
        $hasDocuments = !empty($client['id_front']) && !empty($client['id_back']) && !empty($client['selfie']);
        CLI::write('Documentos enviados: ' . ($hasDocuments ? '✓ SIM' : '✗ NÃO'));
        
        // Verificar CPF
        $isEligible = $clientModel->isClientEligible($clientId);
        CLI::write('Elegível (CPF + Docs): ' . ($isEligible ? '✓ SIM' : '✗ NÃO'));
        
        if ($hasDocuments && $isEligible) {
            CLI::write('🎉 Cliente João Ok já está elegível!', 'green');
        } else {
            CLI::write('⚠️ Problema identificado:', 'yellow');
            if (!$hasDocuments) {
                CLI::write('- Faltam documentos visuais', 'red');
            }
            if (!$isEligible) {
                CLI::write('- Verificação visual ou CPF não aprovados', 'red');
            }
        }
        
        CLI::newLine();
        CLI::write('=== DIAGNÓSTICO ===', 'yellow');
        CLI::write('O problema é que a verificação visual depende da sessão do usuário.');
        CLI::write('Para resolver, você precisa:');
        CLI::write('1. Acessar: http://localhost:8081/clients/verify/' . $clientId, 'cyan');
        CLI::write('2. Clicar em "Aprovar" na seção de Verificação Visual', 'cyan');
        CLI::write('3. Ou modificar o sistema para não depender apenas da sessão', 'cyan');
        
        CLI::newLine();
        CLI::write('Verificando listagem de clientes elegíveis...', 'yellow');
        
        $eligibleClients = $clientModel->getEligibleClients();
        $joaoInList = false;
        
        foreach ($eligibleClients as $eligibleClient) {
            if ($eligibleClient['id'] == $clientId) {
                $joaoInList = true;
                break;
            }
        }
        
        CLI::write('João Ok na lista de elegíveis: ' . ($joaoInList ? '✓ SIM' : '✗ NÃO'));
        
        if (!$joaoInList) {
            CLI::write('⚠️ PROBLEMA CONFIRMADO: João Ok não aparece na lista de clientes elegíveis', 'red');
            CLI::write('Isso significa que a verificação visual não está aprovada na sessão.', 'red');
        }
    }
}