<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\CpfConsultationModel;
use App\Models\ClientModel;

class DebugCpfConsultation extends BaseCommand
{
    protected $group       = 'Debug';
    protected $name        = 'debug:cpf';
    protected $description = 'Debug CPF consultation data';

    public function run(array $params)
    {
        $consultationId = $params[0] ?? null;
        
        if (!$consultationId) {
            CLI::error('Por favor, forneça o ID da consulta: php spark debug:cpf 5');
            return;
        }

        $cpfModel = new CpfConsultationModel();
        $clientModel = new ClientModel();
        
        $consultation = $cpfModel->find($consultationId);
        
        if (!$consultation) {
            CLI::error("Consulta ID {$consultationId} não encontrada");
            return;
        }
        
        $client = $clientModel->find($consultation['client_id']);
        
        CLI::write("=== DEBUG CONSULTA CPF ID: {$consultationId} ===", 'yellow');
        CLI::newLine();
        
        CLI::write("Cliente ID: {$client['id']}", 'green');
        CLI::write("Nome Cliente: '{$client['full_name']}'", 'green');
        CLI::write("Data Nascimento Cliente: '{$client['birth_date']}'", 'green');
        CLI::newLine();
        
        $rawData = json_decode($consultation['raw_json'], true);
        CLI::write("Nome API: '{$rawData['nome']}'", 'blue');
        CLI::write("Data Nascimento API: '{$rawData['nascimento']}'", 'blue');
        CLI::newLine();
        
        CLI::write("Dados Divergentes no Banco: " . ($consultation['dados_divergentes'] ? 'SIM' : 'NÃO'), 'red');
        CLI::newLine();
        
        // Testar comparação
        CLI::write("=== TESTE DE COMPARAÇÃO ===", 'yellow');
        
        // Nome
        $clientName = strtolower(trim($client['full_name']));
        $apiName = strtolower(trim($rawData['nome']));
        $clientNameNorm = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $clientName);
        $apiNameNorm = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $apiName);
        
        CLI::write("Nome Cliente Original: '{$clientName}'");
        CLI::write("Nome Cliente Normalizado: '{$clientNameNorm}'");
        CLI::write("Nome API Original: '{$apiName}'");
        CLI::write("Nome API Normalizado: '{$apiNameNorm}'");
        CLI::write("Nomes Iguais: " . ($clientNameNorm === $apiNameNorm ? 'SIM' : 'NÃO'), $clientNameNorm === $apiNameNorm ? 'green' : 'red');
        CLI::newLine();
        
        // Data
        $clientBirth = date('d/m/Y', strtotime($client['birth_date']));
        $apiBirth = $rawData['nascimento'];
        
        CLI::write("Data Cliente: '{$clientBirth}'");
        CLI::write("Data API: '{$apiBirth}'");
        CLI::write("Datas Iguais: " . ($clientBirth === $apiBirth ? 'SIM' : 'NÃO'), $clientBirth === $apiBirth ? 'green' : 'red');
        CLI::newLine();
        
        $shouldBeDivergent = ($clientNameNorm !== $apiNameNorm) || ($clientBirth !== $apiBirth);
        CLI::write("DEVERIA SER DIVERGENTE: " . ($shouldBeDivergent ? 'SIM' : 'NÃO'), $shouldBeDivergent ? 'red' : 'green');
        CLI::write("ESTÁ MARCADO COMO DIVERGENTE: " . ($consultation['dados_divergentes'] ? 'SIM' : 'NÃO'), $consultation['dados_divergentes'] ? 'red' : 'green');
        
        if ($shouldBeDivergent !== $consultation['dados_divergentes']) {
            CLI::write("❌ INCONSISTÊNCIA DETECTADA!", 'red');
        } else {
            CLI::write("✅ Dados consistentes", 'green');
        }
    }
}