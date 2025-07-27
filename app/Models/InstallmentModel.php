<?php

namespace App\Models;

use CodeIgniter\Model;

class InstallmentModel extends Model
{
    protected $table = 'installments';
    protected $primaryKey = 'id';
    
    protected $useAutoIncrement = true;
    
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    
    protected $allowedFields = [
        'loan_id', 'installment_number', 'due_date', 'amount', 
        'status', 'asaas_payment_id', 'paid_at'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = '';
    
    protected $validationRules = [
        'loan_id' => 'required|integer|greater_than[0]',
        'installment_number' => 'required|integer|greater_than[0]',
        'due_date' => 'required|valid_date',
        'amount' => 'required|decimal|greater_than[0]',
        'status' => 'required|in_list[pending,paid,overdue,cancelled]',
        'asaas_payment_id' => 'permit_empty|max_length[255]'
    ];
    
    protected $validationMessages = [
        'loan_id' => [
            'required' => 'O empréstimo é obrigatório.',
            'integer' => 'O ID do empréstimo deve ser um número válido.',
            'greater_than' => 'O ID do empréstimo deve ser maior que zero.'
        ],
        'installment_number' => [
            'required' => 'O número da parcela é obrigatório.',
            'integer' => 'O número da parcela deve ser um número válido.',
            'greater_than' => 'O número da parcela deve ser maior que zero.'
        ],
        'due_date' => [
            'required' => 'A data de vencimento é obrigatória.',
            'valid_date' => 'A data de vencimento deve ser uma data válida.'
        ],
        'amount' => [
            'required' => 'O valor da parcela é obrigatório.',
            'decimal' => 'O valor da parcela deve ser um número válido.',
            'greater_than' => 'O valor da parcela deve ser maior que zero.'
        ],
        'status' => [
            'required' => 'O status é obrigatório.',
            'in_list' => 'Status inválido.'
        ],
        'asaas_payment_id' => [
            'max_length' => 'ID do pagamento Asaas muito longo.'
        ]
    ];
    
    protected $skipValidation = false;
    protected $cleanValidationRules = true;
    
    protected $allowCallbacks = true;
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];
    
    /**
     * Busca parcelas de um empréstimo específico
     */
    public function getInstallmentsByLoan($loanId)
    {
        return $this->where('loan_id', $loanId)
                   ->orderBy('installment_number', 'ASC')
                   ->findAll();
    }
    
    /**
     * Busca parcelas com dados do empréstimo e cliente
     */
    public function getInstallmentsWithDetails($limit = null, $offset = null)
    {
        $builder = $this->db->table($this->table . ' i')
            ->select('i.*, l.status as loan_status, c.full_name as client_name, c.cpf as client_cpf, 
                     lp.name as plan_name')
            ->join('loans l', 'l.id = i.loan_id', 'left')
            ->join('clients c', 'c.id = l.client_id', 'left')
            ->join('loan_plans lp', 'lp.id = l.loan_plan_id', 'left')
            ->orderBy('i.due_date', 'ASC');
            
        if ($limit !== null) {
            $builder->limit($limit, $offset);
        }
        
        return $builder->get()->getResultArray();
    }
    
    /**
     * Busca uma parcela específica com detalhes
     */
    public function getInstallmentWithDetails($id)
    {
        return $this->db->table($this->table . ' i')
            ->select('i.*, l.status as loan_status, l.client_id, l.loan_plan_id,
                     c.full_name as client_name, c.cpf as client_cpf, c.email as client_email,
                     lp.name as plan_name, lp.loan_amount, lp.total_repayment_amount')
            ->join('loans l', 'l.id = i.loan_id', 'left')
            ->join('clients c', 'c.id = l.client_id', 'left')
            ->join('loan_plans lp', 'lp.id = l.loan_plan_id', 'left')
            ->where('i.id', $id)
            ->get()
            ->getRowArray();
    }
    
    /**
     * Busca parcelas por status
     */
    public function getInstallmentsByStatus($status)
    {
        return $this->where('status', $status)
                   ->orderBy('due_date', 'ASC')
                   ->findAll();
    }
    
    /**
     * Busca parcelas vencidas
     */
    public function getOverdueInstallments()
    {
        return $this->where('status', 'pending')
                   ->where('due_date <', date('Y-m-d'))
                   ->orderBy('due_date', 'ASC')
                   ->findAll();
    }
    
    /**
     * Busca parcelas que vencem hoje
     */
    public function getInstallmentsDueToday()
    {
        return $this->where('status', 'pending')
                   ->where('due_date', date('Y-m-d'))
                   ->orderBy('installment_number', 'ASC')
                   ->findAll();
    }
    
    /**
     * Busca parcelas que vencem nos próximos X dias
     */
    public function getInstallmentsDueSoon($days = 7)
    {
        $futureDate = date('Y-m-d', strtotime("+{$days} days"));
        
        return $this->where('status', 'pending')
                   ->where('due_date >=', date('Y-m-d'))
                   ->where('due_date <=', $futureDate)
                   ->orderBy('due_date', 'ASC')
                   ->findAll();
    }
    
    /**
     * Marca uma parcela como paga
     */
    public function markAsPaid($installmentId, $asaasPaymentId = null)
    {
        $data = [
            'status' => 'paid',
            'paid_at' => date('Y-m-d H:i:s')
        ];
        
        if ($asaasPaymentId) {
            $data['asaas_payment_id'] = $asaasPaymentId;
        }
        
        return $this->update($installmentId, $data);
    }
    
    /**
     * Marca parcelas como vencidas
     */
    public function markOverdueInstallments()
    {
        return $this->where('status', 'pending')
                   ->where('due_date <', date('Y-m-d'))
                   ->set('status', 'overdue')
                   ->update();
    }
    
    /**
     * Cancela todas as parcelas de um empréstimo
     */
    public function cancelInstallmentsByLoan($loanId)
    {
        return $this->where('loan_id', $loanId)
                   ->whereIn('status', ['pending', 'overdue'])
                   ->set('status', 'cancelled')
                   ->update();
    }
    
    /**
     * Cria parcelas para um empréstimo
     */
    public function createInstallmentsForLoan($loanId, $loanPlan, $startDate = null)
    {
        if (!$startDate) {
            $startDate = date('Y-m-d');
        }
        
        $installmentAmount = $loanPlan['total_repayment_amount'] / $loanPlan['number_of_installments'];
        $installments = [];
        
        for ($i = 1; $i <= $loanPlan['number_of_installments']; $i++) {
            $dueDate = date('Y-m-d', strtotime($startDate . " +{$i} month"));
            
            $installments[] = [
                'loan_id' => $loanId,
                'installment_number' => $i,
                'due_date' => $dueDate,
                'amount' => round($installmentAmount, 2),
                'status' => 'pending',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
        }
        
        return $this->insertBatch($installments);
    }
    
    /**
     * Verifica se todas as parcelas de um empréstimo foram pagas
     */
    public function areAllInstallmentsPaid($loanId)
    {
        $totalInstallments = $this->where('loan_id', $loanId)->countAllResults();
        $paidInstallments = $this->where('loan_id', $loanId)
                                ->where('status', 'paid')
                                ->countAllResults();
                                
        return $totalInstallments > 0 && $totalInstallments === $paidInstallments;
    }
    
    /**
     * Estatísticas de parcelas
     */
    public function getStatistics()
    {
        $stats = [];
        
        // Total de parcelas
        $stats['total'] = $this->countAllResults();
        
        // Por status
        $statuses = ['pending', 'paid', 'overdue', 'cancelled'];
        foreach ($statuses as $status) {
            $stats['by_status'][$status] = $this->where('status', $status)->countAllResults(false);
        }
        
        // Valor total das parcelas
        $result = $this->selectSum('amount')->get()->getRowArray();
        $stats['total_amount'] = $result['amount'] ?? 0;
        
        // Valor das parcelas pagas
        $result = $this->selectSum('amount')
                      ->where('status', 'paid')
                      ->get()
                      ->getRowArray();
        $stats['paid_amount'] = $result['amount'] ?? 0;
        
        // Valor das parcelas pendentes
        $result = $this->selectSum('amount')
                      ->where('status', 'pending')
                      ->get()
                      ->getRowArray();
        $stats['pending_amount'] = $result['amount'] ?? 0;
        
        // Valor das parcelas vencidas
        $result = $this->selectSum('amount')
                      ->where('status', 'overdue')
                      ->get()
                      ->getRowArray();
        $stats['overdue_amount'] = $result['amount'] ?? 0;
        
        return $stats;
    }
}