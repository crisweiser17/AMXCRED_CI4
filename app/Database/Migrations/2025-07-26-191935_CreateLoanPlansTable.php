<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLoanPlansTable extends Migration
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
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => false,
                'comment' => 'Nome comercial do plano. Ex: Plano Bronze, Empréstimo Rápido 500',
            ],
            'loan_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => false,
                'comment' => 'O valor exato que será depositado na conta do cliente (o principal).',
            ],
            'total_repayment_amount' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => false,
                'comment' => 'A soma de tudo que o cliente deverá pagar (principal + todos os juros).',
            ],
            'number_of_installments' => [
                'type' => 'INT',
                'constraint' => 11,
                'null' => false,
                'comment' => 'O número total de parcelas que serão geradas.',
            ],
            'is_active' => [
                'type' => 'BOOLEAN',
                'default' => true,
                'null' => false,
                'comment' => 'Flag para ativar/desativar o plano. Se FALSE, não deve aparecer na lista de seleção.',
            ],
            'created_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'TIMESTAMP',
                'null' => true,
            ],
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->addKey('is_active');
        $this->forge->addKey('name');
        $this->forge->createTable('loan_plans');
    }

    public function down()
    {
        $this->forge->dropTable('loan_plans');
    }
}
