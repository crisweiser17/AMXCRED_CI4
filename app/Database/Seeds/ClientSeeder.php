<?php
namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ClientSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'full_name' => 'João Silva',
                'cpf' => '123.456.789-09',
                'email' => 'joao.silva@example.com',
                'phone' => '(11) 98765-4321',
                'birth_date' => '1990-05-15',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'full_name' => 'Maria Santos',
                'cpf' => '987.654.321-00',
                'email' => 'maria.santos@example.com',
                'phone' => '(21) 99876-5432',
                'birth_date' => '1985-08-22',
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'full_name' => 'Pedro Oliveira',
                'cpf' => '456.789.123-45',
                'email' => 'pedro.oliveira@example.com',
                'phone' => '(31) 91234-5678',
                'birth_date' => '1992-03-10',
                'created_at' => date('Y-m-d H:i:s')
            ]
        ];

        $this->db->table('clients')->insertBatch($data);
    }
}