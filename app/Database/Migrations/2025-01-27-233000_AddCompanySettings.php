<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCompanySettings extends Migration
{
    public function up()
    {
        // Inserir configurações da empresa na tabela settings
        $data = [
            [
                'key' => 'company_name',
                'value' => 'AMX Cred',
                'description' => 'Nome da empresa exibido no cabeçalho e rodapé do site público',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'company_logo_url',
                'value' => '',
                'description' => 'URL do logotipo da empresa (deixe vazio para usar ícone padrão)',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'company_slogan',
                'value' => 'Empréstimos Rápidos e Seguros',
                'description' => 'Slogan da empresa exibido no cabeçalho do site público',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'company_email',
                'value' => 'contato@amxcred.com.br',
                'description' => 'E-mail de contato da empresa exibido no rodapé',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'company_whatsapp',
                'value' => '(11) 99999-9999',
                'description' => 'Número do WhatsApp da empresa exibido no rodapé',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'company_phone',
                'value' => '(11) 3333-4444',
                'description' => 'Telefone fixo da empresa exibido no rodapé',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        $this->db->table('settings')->insertBatch($data);
    }

    public function down()
    {
        // Remover configurações da empresa
        $keys = [
            'company_name',
            'company_logo_url', 
            'company_slogan',
            'company_email',
            'company_whatsapp',
            'company_phone'
        ];

        $this->db->table('settings')->whereIn('key', $keys)->delete();
    }
}