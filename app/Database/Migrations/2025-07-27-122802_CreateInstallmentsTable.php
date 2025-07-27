<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInstallmentsTable extends Migration
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
            'installment_number' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'due_date' => [
                'type' => 'DATE',
            ],
            'amount' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pending', 'paid', 'overdue', 'cancelled'],
                'default' => 'pending',
            ],
            'asaas_payment_id' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'paid_at' => [
                'type' => 'DATETIME',
                'null' => true,
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
        $this->forge->addKey(['loan_id', 'installment_number'], false, true); // Unique constraint
        $this->forge->addKey('due_date');
        $this->forge->addKey('status');
        $this->forge->addKey('asaas_payment_id');
        
        $this->forge->addForeignKey('loan_id', 'loans', 'id', 'CASCADE', 'CASCADE');
        
        $this->forge->createTable('installments');
    }

    public function down()
    {
        $this->forge->dropTable('installments');
    }
}
