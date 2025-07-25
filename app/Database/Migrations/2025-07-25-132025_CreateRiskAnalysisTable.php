<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRiskAnalysisTable extends Migration
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
            'dividas_bancarias' => [
                'type' => 'BOOLEAN',
                'null' => true,
                'comment' => 'Cliente possui dívidas bancárias',
            ],
            'cheque_sem_fundo' => [
                'type' => 'BOOLEAN',
                'null' => true,
                'comment' => 'Cliente possui histórico de cheque sem fundo',
            ],
            'protesto_nacional' => [
                'type' => 'BOOLEAN',
                'null' => true,
                'comment' => 'Cliente possui protestos nacionais',
            ],
            'score' => [
                'type' => 'INT',
                'constraint' => 4,
                'null' => true,
                'comment' => 'Score de crédito (0-1000)',
            ],
            'recomendacao_serasa' => [
                'type' => 'TEXT',
                'null' => true,
                'comment' => 'Recomendação textual do Serasa',
            ],
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['pendente', 'consultado'],
                'default' => 'pendente',
                'null' => false,
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
        $this->forge->createTable('risk_analysis');
    }

    public function down()
    {
        $this->forge->dropTable('risk_analysis');
    }
}
