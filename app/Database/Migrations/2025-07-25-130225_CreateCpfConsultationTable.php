<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCpfConsultationTable extends Migration
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
            'client_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => false,
            ],
            'raw_json' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'JSON completo retornado da API',
            ],
            'cpf_valido' => [
                'type' => 'BOOLEAN',
                'null' => false,
                'default' => false,
                'comment' => 'CPF válido e existente na Receita Federal',
            ],
            'cpf_regular' => [
                'type' => 'BOOLEAN',
                'null' => false,
                'default' => false,
                'comment' => 'Situação regular (ativo)',
            ],
            'dados_divergentes' => [
                'type' => 'BOOLEAN',
                'null' => false,
                'default' => false,
                'comment' => 'Nome e data de nascimento divergem dos dados da API',
            ],
            'obito' => [
                'type' => 'BOOLEAN',
                'null' => false,
                'default' => false,
                'comment' => 'CPF consta como falecido',
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pendente', 'aprovado', 'reprovado'],
                'default' => 'pendente',
                'null' => false,
            ],
            'motivo_reprovacao' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'comment' => 'Motivo da reprovação: cpf_invalido, cpf_irregular, dados_divergentes, obito',
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
        $this->forge->addKey('client_id');
        $this->forge->addForeignKey('client_id', 'clients', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('cpf_consultation');
    }

    public function down()
    {
        $this->forge->dropTable('cpf_consultation');
    }
}
