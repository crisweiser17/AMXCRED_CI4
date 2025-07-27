<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAsaasCustomerIdToClients extends Migration
{
    public function up()
    {
        $this->forge->addColumn('clients', [
            'asaas_customer_id' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'email',
            ],
        ]);
        
        // Adicionar índice para o campo asaas_customer_id
        $this->forge->addKey('asaas_customer_id', false, false, 'clients');
    }

    public function down()
    {
        $this->forge->dropColumn('clients', 'asaas_customer_id');
    }
}
