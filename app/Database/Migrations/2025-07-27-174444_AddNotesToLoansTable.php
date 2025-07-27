<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddNotesToLoansTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('loans', [
            'notes' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Observações sobre o empréstimo',
                'after' => 'funded_by_user_id'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('loans', 'notes');
    }
}
