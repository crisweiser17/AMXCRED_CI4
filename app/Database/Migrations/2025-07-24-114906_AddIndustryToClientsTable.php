<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIndustryToClientsTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('clients', [
            'industry' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'occupation',
                'comment' => 'Setor de atividade/indústria do cliente',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('clients', 'industry');
    }
}
