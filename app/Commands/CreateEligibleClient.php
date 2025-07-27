<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class CreateEligibleClient extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'db:create-eligible-client';
    protected $description = 'Cria um cliente elegível para empréstimos';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        
        CLI::write('Atualizando cliente 1 com documentos visuais...', 'yellow');
        
        // Atualizar cliente 1 com documentos visuais
        $result = $db->table('clients')
            ->where('id', 1)
            ->update([
                'id_front' => 'id_front.jpg',
                'id_back' => 'id_back.jpg', 
                'selfie' => 'selfie.jpg'
            ]);

        if ($result) {
            CLI::write('✓ Cliente 1 atualizado com documentos visuais', 'green');
        } else {
            CLI::write('✗ Erro ao atualizar cliente 1', 'red');
            return;
        }

        CLI::write('Atualizando consulta de CPF para aprovado...', 'yellow');
        
        // Atualizar especificamente o registro ID 6 (mais recente do cliente 1)
        $cpfResult = $db->table('cpf_consultation')
            ->where('id', 6)
            ->update([
                'cpf_valido' => 1,
                'cpf_regular' => 1,
                'dados_divergentes' => 0,
                'obito' => 0,
                'status' => 'aprovado',
                'motivo_reprovacao' => null,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

        if ($cpfResult) {
            CLI::write('✓ Consulta de CPF aprovada criada para cliente 1', 'green');
        } else {
            CLI::write('✗ Erro ao criar consulta de CPF', 'red');
            return;
        }

        CLI::write('', 'white');
        CLI::write('🎉 Cliente 1 (João Silva) agora está elegível para empréstimos!', 'green');
        CLI::write('Você pode acessar /loans/create para criar um empréstimo.', 'cyan');
    }
}