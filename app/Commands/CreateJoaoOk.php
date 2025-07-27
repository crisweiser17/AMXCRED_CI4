<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\ClientModel;
use App\Models\CpfConsultationModel;

class CreateJoaoOk extends BaseCommand
{
    protected $group       = 'Custom';
    protected $name        = 'create:joao-ok';
    protected $description = 'Cria o cliente João Ok aprovado e elegível para empréstimos';

    public function run(array $params)
    {
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
            'selfie' => 'joao_ok_selfie.jpg'
        ];

        try {
            // Verificar se já existe um cliente com este CPF
            $existingClient = $clientModel->where('cpf', $clientData['cpf'])->first();
            if ($existingClient) {
                CLI::error('Já existe um cliente com o CPF ' . $clientData['cpf']);
                CLI::write('Cliente existente: ' . $existingClient['full_name'] . ' (ID: ' . $existingClient['id'] . ')');
                return;
            }

            // Inserir o cliente
            $clientId = $clientModel->insert($clientData);
            
            if (!$clientId) {
                throw new \Exception('Erro ao criar cliente: ' . implode(', ', $clientModel->errors()));
            }
            
            CLI::write("Cliente João Ok criado com ID: {$clientId}", 'green');
            
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
                'motivo_reprovacao' => null
            ];
            
            $consultationId = $cpfConsultationModel->insert($cpfData);
            
            if (!$consultationId) {
                throw new \Exception('Erro ao criar consulta CPF: ' . implode(', ', $cpfConsultationModel->errors()));
            }
            
            CLI::write("Consulta CPF aprovada criada com ID: {$consultationId}", 'green');
            
            // Verificar elegibilidade
            $isEligible = $clientModel->isClientEligible($clientId);
            
            CLI::newLine();
            CLI::write('=== RESUMO ===', 'yellow');
            CLI::write("Cliente: {$clientData['full_name']}");
            CLI::write("ID: {$clientId}");
            CLI::write("CPF: {$clientData['cpf']}");
            CLI::write("Email: {$clientData['email']}");
            CLI::write("Documentos: ✓ RG Frente, ✓ RG Verso, ✓ Selfie");
            CLI::write("Verificação Visual: ⚠️ Pendente (precisa ser aprovada manualmente)");
            CLI::write("Consulta CPF: ✓ Aprovada");
            CLI::write("Elegível para empréstimos: " . ($isEligible ? '✓ SIM' : '⚠️ NÃO (falta aprovação visual)'));
            
            CLI::newLine();
            if ($isEligible) {
                CLI::write('🎉 Cliente João Ok criado com sucesso e está elegível para empréstimos!', 'green');
            } else {
                CLI::write('⚠️ Cliente criado, mas precisa da aprovação visual para ficar elegível.', 'yellow');
                CLI::write('Para aprovar a verificação visual:', 'yellow');
                CLI::write('1. Acesse: http://localhost:8081/clients/verify/' . $clientId, 'cyan');
                CLI::write('2. Clique em "Aprovar" na seção de Verificação de Documentos', 'cyan');
            }
            
            CLI::write("Acesse: http://localhost:8081/clients/verify/{$clientId} para visualizar", 'cyan');
            
        } catch (\Exception $e) {
            CLI::error('Erro: ' . $e->getMessage());
            return;
        }
    }
}