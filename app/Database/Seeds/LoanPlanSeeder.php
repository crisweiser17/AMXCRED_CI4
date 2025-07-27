<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class LoanPlanSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name' => 'Plano Bronze',
                'loan_amount' => 500.00,
                'total_repayment_amount' => 660.00,
                'number_of_installments' => 6,
                'is_active' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Plano Prata',
                'loan_amount' => 1000.00,
                'total_repayment_amount' => 1320.00,
                'number_of_installments' => 6,
                'is_active' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Plano Ouro',
                'loan_amount' => 2000.00,
                'total_repayment_amount' => 2800.00,
                'number_of_installments' => 8,
                'is_active' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Empréstimo Rápido 300',
                'loan_amount' => 300.00,
                'total_repayment_amount' => 375.00,
                'number_of_installments' => 3,
                'is_active' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Plano Especial 1500',
                'loan_amount' => 1500.00,
                'total_repayment_amount' => 2100.00,
                'number_of_installments' => 10,
                'is_active' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'name' => 'Plano Descontinuado',
                'loan_amount' => 800.00,
                'total_repayment_amount' => 1000.00,
                'number_of_installments' => 5,
                'is_active' => false,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ]
        ];

        // Inserir os dados na tabela
        $this->db->table('loan_plans')->insertBatch($data);
        
        echo "Planos de empréstimo inseridos com sucesso!\n";
        echo "- 5 planos ativos\n";
        echo "- 1 plano inativo (para demonstrar funcionalidade)\n";
        echo "- Diferentes valores e parcelas para demonstrar flexibilidade\n";
    }
}
