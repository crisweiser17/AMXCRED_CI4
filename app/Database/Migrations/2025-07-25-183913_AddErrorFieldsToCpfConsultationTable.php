<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddErrorFieldsToCpfConsultationTable extends Migration
{
    public function up()
    {
        $fields = [
            'ano_obito' => [
                'type' => 'INT',
                'constraint' => 4,
                'null' => true,
                'comment' => 'Ano do óbito se disponível'
            ],
            'codigo_erro' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => true,
                'comment' => 'Código de erro da API quando status = 0'
            ],
            'mensagem_erro' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Mensagem de erro da API quando status = 0'
            ]
        ];

        $this->forge->addColumn('cpf_consultation', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('cpf_consultation', ['ano_obito', 'codigo_erro', 'mensagem_erro']);
    }
}
