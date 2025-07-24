<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSettingsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'category' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
                'comment' => 'Categoria da configuração (ex: client_required_fields, smtp_config, etc.)',
            ],
            'key' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
                'comment' => 'Chave da configuração (ex: full_name, email, etc.)',
            ],
            'value' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Valor da configuração',
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Descrição da configuração',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['category', 'key']);
        $this->forge->createTable('settings');
        
        // Inserir configurações padrão para campos obrigatórios
        $data = [
            // Dados Pessoais - sempre obrigatórios
            ['category' => 'client_required_fields', 'key' => 'full_name', 'value' => 'true', 'description' => 'Nome Completo (sempre obrigatório)'],
            ['category' => 'client_required_fields', 'key' => 'cpf', 'value' => 'true', 'description' => 'CPF (sempre obrigatório)'],
            ['category' => 'client_required_fields', 'key' => 'email', 'value' => 'true', 'description' => 'Email'],
            ['category' => 'client_required_fields', 'key' => 'phone', 'value' => 'true', 'description' => 'Telefone'],
            ['category' => 'client_required_fields', 'key' => 'birth_date', 'value' => 'true', 'description' => 'Data de Nascimento'],
            
            // Dados Profissionais
            ['category' => 'client_required_fields', 'key' => 'occupation', 'value' => 'false', 'description' => 'Ocupação'],
            ['category' => 'client_required_fields', 'key' => 'industry', 'value' => 'false', 'description' => 'Indústria/Setor'],
            ['category' => 'client_required_fields', 'key' => 'employment_duration', 'value' => 'false', 'description' => 'Tempo de Trabalho'],
            ['category' => 'client_required_fields', 'key' => 'monthly_income', 'value' => 'false', 'description' => 'Renda Mensal'],
            
            // Dados PIX
            ['category' => 'client_required_fields', 'key' => 'pix_key_type', 'value' => 'false', 'description' => 'Tipo de Chave PIX'],
            ['category' => 'client_required_fields', 'key' => 'pix_key', 'value' => 'false', 'description' => 'Chave PIX'],
            
            // Endereço
            ['category' => 'client_required_fields', 'key' => 'zip_code', 'value' => 'false', 'description' => 'CEP'],
            ['category' => 'client_required_fields', 'key' => 'street', 'value' => 'false', 'description' => 'Rua'],
            ['category' => 'client_required_fields', 'key' => 'number', 'value' => 'false', 'description' => 'Número'],
            ['category' => 'client_required_fields', 'key' => 'complement', 'value' => 'false', 'description' => 'Complemento'],
            ['category' => 'client_required_fields', 'key' => 'neighborhood', 'value' => 'false', 'description' => 'Bairro'],
            ['category' => 'client_required_fields', 'key' => 'city', 'value' => 'false', 'description' => 'Cidade'],
            ['category' => 'client_required_fields', 'key' => 'state', 'value' => 'false', 'description' => 'UF'],
            
            // Documentos
            ['category' => 'client_required_fields', 'key' => 'payslip_1', 'value' => 'false', 'description' => '1º Comprovante de Renda'],
            ['category' => 'client_required_fields', 'key' => 'payslip_2', 'value' => 'false', 'description' => '2º Comprovante de Renda'],
            ['category' => 'client_required_fields', 'key' => 'payslip_3', 'value' => 'false', 'description' => '3º Comprovante de Renda'],
            ['category' => 'client_required_fields', 'key' => 'id_front', 'value' => 'false', 'description' => 'RG Frente'],
            ['category' => 'client_required_fields', 'key' => 'id_back', 'value' => 'false', 'description' => 'RG Verso'],
            ['category' => 'client_required_fields', 'key' => 'selfie', 'value' => 'false', 'description' => 'Selfie'],
        ];
        
        foreach ($data as $setting) {
            $setting['created_at'] = date('Y-m-d H:i:s');
            $setting['updated_at'] = date('Y-m-d H:i:s');
        }
        
        $this->db->table('settings')->insertBatch($data);
    }

    public function down()
    {
        $this->forge->dropTable('settings');
    }
}
