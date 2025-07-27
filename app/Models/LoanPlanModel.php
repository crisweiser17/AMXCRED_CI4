<?php

namespace App\Models;

use CodeIgniter\Model;

class LoanPlanModel extends Model
{
    protected $table = 'loan_plans';
    protected $primaryKey = 'id';
    
    protected $useAutoIncrement = true;
    
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    
    protected $allowedFields = [
        'name', 'loan_amount', 'total_repayment_amount', 
        'number_of_installments', 'is_active'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = '';
    
    protected $validationRules = [
        'name' => 'required|min_length[3]|max_length[100]|is_unique[loan_plans.name,id,{id}]',
        'loan_amount' => 'required|decimal|greater_than[0]',
        'total_repayment_amount' => 'required|decimal|greater_than[0]',
        'number_of_installments' => 'required|integer|greater_than[0]',
        'is_active' => 'permit_empty|in_list[0,1]'
    ];
    
    protected $validationMessages = [
        'name' => [
            'required' => 'O nome do plano é obrigatório.',
            'min_length' => 'O nome deve ter pelo menos 3 caracteres.',
            'max_length' => 'O nome não pode ter mais de 100 caracteres.',
            'is_unique' => 'Já existe um plano com este nome.'
        ],
        'loan_amount' => [
            'required' => 'O valor do empréstimo é obrigatório.',
            'decimal' => 'O valor do empréstimo deve ser um número válido.',
            'greater_than' => 'O valor do empréstimo deve ser maior que zero.'
        ],
        'total_repayment_amount' => [
            'required' => 'O valor total a pagar é obrigatório.',
            'decimal' => 'O valor total deve ser um número válido.',
            'greater_than' => 'O valor total deve ser maior que zero.'
        ],
        'number_of_installments' => [
            'required' => 'O número de parcelas é obrigatório.',
            'integer' => 'O número de parcelas deve ser um número inteiro.',
            'greater_than' => 'O número de parcelas deve ser maior que zero.'
        ]
    ];

    /**
     * Validação customizada para garantir que o total seja maior que o valor do empréstimo
     */
    protected $beforeInsert = ['validateTotalAmount'];
    protected $beforeUpdate = ['validateTotalAmount'];

    protected function validateTotalAmount(array $data)
    {
        if (isset($data['data']['loan_amount']) && isset($data['data']['total_repayment_amount'])) {
            $loanAmount = (float) $data['data']['loan_amount'];
            $totalAmount = (float) $data['data']['total_repayment_amount'];
            
            if ($totalAmount <= $loanAmount) {
                // Usar o sistema de validação do CodeIgniter
                $this->validation->setError('total_repayment_amount', 'O valor total a pagar deve ser maior que o valor do empréstimo.');
                return false;
            }
        }
        
        return $data;
    }

    /**
     * Validação adicional para verificar se o total é maior que o empréstimo
     */
    public function validateAmounts($data)
    {
        $loanAmount = (float) ($data['loan_amount'] ?? 0);
        $totalAmount = (float) ($data['total_repayment_amount'] ?? 0);
        
        if ($totalAmount <= $loanAmount) {
            return false;
        }
        
        return true;
    }

    /**
     * Calcula a taxa de juros mensal efetiva
     */
    public function getMonthlyInterestRate($loanAmount, $totalAmount, $installments)
    {
        if ($loanAmount <= 0 || $installments <= 0 || $totalAmount <= $loanAmount) {
            return 0;
        }
        
        // Fórmula: ((Total/Principal)^(1/Parcelas)) - 1
        $rate = pow(($totalAmount / $loanAmount), (1 / $installments)) - 1;
        return round($rate * 100, 2);
    }

    /**
     * Calcula o valor de cada parcela
     */
    public function getInstallmentAmount($totalAmount, $installments)
    {
        if ($installments <= 0) {
            return 0;
        }
        
        return round($totalAmount / $installments, 2);
    }

    /**
     * Calcula o valor total dos juros
     */
    public function getTotalInterest($loanAmount, $totalAmount)
    {
        return $totalAmount - $loanAmount;
    }

    /**
     * Retorna apenas planos ativos (para uso em dropdowns futuros)
     */
    public function getActivePlans()
    {
        return $this->where('is_active', true)
                   ->orderBy('name', 'ASC')
                   ->findAll();
    }

    /**
     * Retorna todos os planos com cálculos adicionais
     */
    public function getAllWithCalculations()
    {
        $plans = $this->orderBy('created_at', 'DESC')->findAll();
        
        foreach ($plans as &$plan) {
            $plan['installment_amount'] = $this->getInstallmentAmount(
                $plan['total_repayment_amount'], 
                $plan['number_of_installments']
            );
            $plan['total_interest'] = $this->getTotalInterest(
                $plan['loan_amount'], 
                $plan['total_repayment_amount']
            );
            $plan['monthly_interest_rate'] = $this->getMonthlyInterestRate(
                $plan['loan_amount'], 
                $plan['total_repayment_amount'], 
                $plan['number_of_installments']
            );
        }
        
        return $plans;
    }

    /**
     * Retorna um plano específico com cálculos
     */
    public function findWithCalculations($id)
    {
        $plan = $this->find($id);
        
        if ($plan) {
            $plan['installment_amount'] = $this->getInstallmentAmount(
                $plan['total_repayment_amount'], 
                $plan['number_of_installments']
            );
            $plan['total_interest'] = $this->getTotalInterest(
                $plan['loan_amount'], 
                $plan['total_repayment_amount']
            );
            $plan['monthly_interest_rate'] = $this->getMonthlyInterestRate(
                $plan['loan_amount'], 
                $plan['total_repayment_amount'], 
                $plan['number_of_installments']
            );
        }
        
        return $plan;
    }

    /**
     * Alterna o status ativo/inativo de um plano
     */
    public function toggleStatus($id)
    {
        $plan = $this->find($id);
        
        if ($plan) {
            $newStatus = !$plan['is_active'];
            return $this->update($id, ['is_active' => $newStatus]);
        }
        
        return false;
    }
}