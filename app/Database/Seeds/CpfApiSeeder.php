<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CpfApiSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'category' => 'cpf_api',
                'key' => 'cpf_api_environment',
                'value' => 'test',
                'description' => 'Ambiente da API CPF (test/production)',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'category' => 'cpf_api',
                'key' => 'cpf_api_test_token',
                'value' => '5ae973d7a997af13f0aaf2bf60e65803',
                'description' => 'Token de teste da API CPF',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'category' => 'cpf_api',
                'key' => 'cpf_api_production_token',
                'value' => '',
                'description' => 'Token de produção da API CPF',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'category' => 'cpf_api',
                'key' => 'cpf_api_test_url',
                'value' => 'https://api.cpfcnpj.com.br/test',
                'description' => 'URL de teste da API CPF',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'category' => 'cpf_api',
                'key' => 'cpf_api_production_url',
                'value' => 'https://api.cpfcnpj.com.br',
                'description' => 'URL de produção da API CPF',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        // Usar replace para inserir ou atualizar
        foreach ($data as $setting) {
            $this->db->table('settings')->replace($setting);
        }
    }
}