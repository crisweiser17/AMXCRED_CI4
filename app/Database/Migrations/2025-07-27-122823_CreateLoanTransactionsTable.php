<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLoanTransactionsTable extends Migration
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
            'loan_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'client_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'type' => [
                'type' => 'ENUM',
                'constraint' => ['debit', 'credit'],
            ],
            'category' => [
                'type' => 'ENUM',
                'constraint' => ['loan_disbursement', 'installment_payment', 'fee', 'interest', 'penalty', 'refund'],
            ],
            'amount' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'origin' => [
                'type' => 'ENUM',
                'constraint' => ['manual', 'asaas_webhook', 'system'],
            ],
            'related_installment_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'created_by_user_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'null' => true,
            ],
            'transaction_date' => [
                'type' => 'DATETIME',
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
        $this->forge->addKey('loan_id');
        $this->forge->addKey('client_id');
        $this->forge->addKey('type');
        $this->forge->addKey('category');
        $this->forge->addKey('origin');
        $this->forge->addKey('related_installment_id');
        $this->forge->addKey('transaction_date');
        
        $this->forge->addForeignKey('loan_id', 'loans', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('client_id', 'clients', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('related_installment_id', 'installments', 'id', 'SET NULL', 'CASCADE');
        $this->forge->addForeignKey('created_by_user_id', 'users', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('loan_transactions');
    }

    public function down()
    {
        $this->forge->dropTable('loan_transactions');
    }
}
