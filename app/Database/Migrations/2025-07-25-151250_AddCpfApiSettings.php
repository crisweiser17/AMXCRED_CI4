<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCpfApiSettings extends Migration
{
    public function up()
    {
        // Inserir configurações padrão da API CPF
        $data = [
            [
                'key' => 'cpf_api_environment',
                'value' => 'test',
                'description' => 'Ambiente da API CPF (test ou production)',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'cpf_api_test_token',
                'value' => '5ae973d7a997af13f0aaf2bf60e65803',
                'description' => 'Token de teste da API CPF',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'cpf_api_production_token',
                'value' => '',
                'description' => 'Token de produção da API CPF',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'cpf_api_test_url',
                'value' => 'https://api.cpfcnpj.com.br/test',
                'description' => 'URL base da API CPF para teste',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'key' => 'cpf_api_production_url',
                'value' => 'https://api.cpfcnpj.com.br',
                'description' => 'URL base da API CPF para produção',
                'created_at' => date('Y-m-d H:i:s')
            ]
        ];

        $this->db->table('settings')->insertBatch($data);
    }

    public function down()
    {
        // Remover configurações da API CPF
        $keys = [
            'cpf_api_environment',
            'cpf_api_test_token',
            'cpf_api_production_token',
            'cpf_api_test_url',
            'cpf_api_production_url'
        ];

        $this->db->table('settings')->whereIn('key', $keys)->delete();
    }
}
