<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateClientsTable extends Migration
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
            'full_name' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => false,
            ],
            'cpf' => [
                'type' => 'VARCHAR',
                'constraint' => 14,
                'null' => false,
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => false,
            ],
            'phone' => [
                'type' => 'VARCHAR',
                'constraint' => 20,
                'null' => false,
            ],
            'birth_date' => [
                'type' => 'DATE',
                'null' => false,
            ],
            'occupation' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'employment_duration' => [
                'type' => 'INT',
                'null' => true,
                'comment' => 'em meses',
            ],
            'monthly_income' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
            ],
            'pix_key_type' => [
                'type' => 'ENUM',
                'constraint' => ['cpf', 'email', 'phone', 'random'],
                'null' => true,
            ],
            'pix_key' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => true,
            ],
            'zip_code' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => true,
            ],
            'street' => [
                'type' => 'VARCHAR',
                'constraint' => 150,
                'null' => true,
            ],
            'number' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => true,
            ],
            'complement' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ],
            'neighborhood' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'city' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ],
            'state' => [
                'type' => 'CHAR',
                'constraint' => 2,
                'null' => true,
            ],
            'payslip_1' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'comment' => 'imagem',
            ],
            'payslip_2' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'comment' => 'imagem',
            ],
            'payslip_3' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'comment' => 'imagem',
            ],
            'id_front' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'comment' => 'imagem',
            ],
            'id_back' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'comment' => 'imagem',
            ],
            'selfie' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'comment' => 'imagem',
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
        $this->forge->addUniqueKey('cpf');
        $this->forge->addUniqueKey('email');
        $this->forge->createTable('clients');
    }

    public function down()
    {
        $this->forge->dropTable('clients');
    }
}
