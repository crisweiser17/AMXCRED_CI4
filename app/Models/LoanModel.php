<?php

namespace App\Models;

use CodeIgniter\Model;

class LoanModel extends Model
{
    protected $table = 'loans';
    protected $primaryKey = 'id';
    
    protected $useAutoIncrement = true;
    
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    
    protected $allowedFields = [
        'client_id', 'loan_plan_id', 'status', 'acceptance_token', 
        'token_expires_at', 'accepted_at', 'funded_at', 
        'funding_pix_transaction_id', 'funded_by_user_id', 'notes'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = '';
    
    protected $validationRules = [
        'client_id' => 'required|integer|greater_than[0]',
        'loan_plan_id' => 'required|integer|greater_than[0]',
        'status' => 'required|in_list[pending_acceptance,accepted,pending_funding,funded,active,completed,cancelled,defaulted]',
        'acceptance_token' => 'permit_empty|max_length[255]',
        'funding_pix_transaction_id' => 'permit_empty|max_length[255]',
        'funded_by_user_id' => 'permit_empty|integer|greater_than[0]'
    ];
    
    protected $validationMessages = [
        'client_id' => [
            'required' => 'O cliente é obrigatório.',
            'integer' => 'O ID do cliente deve ser um número válido.',
            'greater_than' => 'O ID do cliente deve ser maior que zero.'
        ],
        'loan_plan_id' => [
            'required' => 'O plano de empréstimo é obrigatório.',
            'integer' => 'O ID do plano deve ser um número válido.',
            'greater_than' => 'O ID do plano deve ser maior que zero.'
        ],
        'status' => [
            'required' => 'O status é obrigatório.',
            'in_list' => 'Status inválido.'
        ],
        'acceptance_token' => [
            'max_length' => 'Token de aceitação muito longo.'
        ],
        'funding_pix_transaction_id' => [
            'max_length' => 'ID da transação PIX muito longo.'
        ],
        'funded_by_user_id' => [
            'integer' => 'O ID do usuário deve ser um número válido.',
            'greater_than' => 'O ID do usuário deve ser maior que zero.'
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
     * Busca empréstimos com dados do cliente e plano
     */
    public function getLoansWithDetails($limit = null, $offset = null)
    {
        $builder = $this->db->table($this->table . ' l')
            ->select('l.*, c.full_name as client_name, c.cpf as client_cpf, c.email as client_email, 
                     lp.name as plan_name, lp.loan_amount, lp.total_repayment_amount, lp.number_of_installments')
            ->join('clients c', 'c.id = l.client_id', 'left')
            ->join('loan_plans lp', 'lp.id = l.loan_plan_id', 'left')
            ->orderBy('l.created_at', 'DESC');
            
        if ($limit !== null) {
            $builder->limit($limit, $offset);
        }
        
        return $builder->get()->getResultArray();
    }
    
    /**
     * Busca um empréstimo específico com detalhes
     */
    public function getLoanWithDetails($id)
    {
        $loan = $this->db->table($this->table . ' l')
            ->select('l.*, c.full_name as client_name, c.cpf as client_cpf, c.email as client_email, 
                     c.phone as client_phone,
                     lp.name as plan_name, lp.loan_amount, lp.total_repayment_amount, lp.number_of_installments,
                     u.name as funded_by_name')
            ->join('clients c', 'c.id = l.client_id', 'left')
            ->join('loan_plans lp', 'lp.id = l.loan_plan_id', 'left')
            ->join('users u', 'u.id = l.funded_by_user_id', 'left')
            ->where('l.id', $id)
            ->get()
            ->getRowArray();
            
        // Calcular taxa de juros mensal se os dados estão disponíveis
        if ($loan && $loan['loan_amount'] > 0 && $loan['number_of_installments'] > 0 && $loan['total_repayment_amount'] > $loan['loan_amount']) {
            $rate = pow(($loan['total_repayment_amount'] / $loan['loan_amount']), (1 / $loan['number_of_installments'])) - 1;
            $loan['monthly_interest_rate'] = round($rate * 100, 2);
        } else {
            $loan['monthly_interest_rate'] = 0;
        }
        
        return $loan;
    }
    
    /**
     * Busca empréstimos por cliente
     */
    public function getLoansByClient($clientId)
    {
        return $this->where('client_id', $clientId)
                   ->orderBy('created_at', 'DESC')
                   ->findAll();
    }
    
    /**
     * Busca empréstimos por status
     */
    public function getLoansByStatus($status)
    {
        return $this->where('status', $status)
                   ->orderBy('created_at', 'DESC')
                   ->findAll();
    }
    
    /**
     * Gera token único para aceitação do empréstimo
     */
    public function generateAcceptanceToken()
    {
        return bin2hex(random_bytes(32));
    }
    
    /**
     * Busca empréstimo pelo token de aceitação
     */
    public function getLoanByToken($token)
    {
        return $this->db->table($this->table . ' l')
            ->select('l.*, c.full_name as client_name, c.cpf as client_cpf, c.email as client_email, 
                     c.phone as client_phone,
                     lp.name as plan_name, lp.loan_amount, lp.total_repayment_amount, lp.number_of_installments')
            ->join('clients c', 'c.id = l.client_id', 'left')
            ->join('loan_plans lp', 'lp.id = l.loan_plan_id', 'left')
            ->where('l.acceptance_token', $token)
            ->get()
            ->getRowArray();
    }
    
    /**
     * Valida se um token de aceitação é válido
     */
    public function validateAcceptanceToken($token)
    {
        $loan = $this->where('acceptance_token', $token)
                    ->where('token_expires_at >', date('Y-m-d H:i:s'))
                    ->where('status', 'pending_acceptance')
                    ->first();
                    
        return $loan !== null ? $loan : false;
    }
    
    /**
     * Aceita um empréstimo usando o token
     */
    public function acceptLoan($token)
    {
        $loan = $this->validateAcceptanceToken($token);
        
        if (!$loan) {
            return false;
        }
        
        return $this->update($loan['id'], [
            'status' => 'accepted',
            'accepted_at' => date('Y-m-d H:i:s'),
            'acceptance_token' => null,
            'token_expires_at' => null
        ]);
    }
    
    /**
     * Recusa um empréstimo usando o token
     */
    public function rejectLoan($token)
    {
        $loan = $this->validateAcceptanceToken($token);
        
        if (!$loan) {
            return false;
        }
        
        return $this->update($loan['id'], [
            'status' => 'cancelled',
            'cancelled_at' => date('Y-m-d H:i:s'),
            'acceptance_token' => null,
            'token_expires_at' => null
        ]);
    }
    
    /**
     * Marca empréstimo como financiado
     */
    public function fundLoan($loanId, $userId, $pixTransactionId = null)
    {
        return $this->update($loanId, [
            'status' => 'funded',
            'funded_at' => date('Y-m-d H:i:s'),
            'funded_by_user_id' => $userId,
            'funding_pix_transaction_id' => $pixTransactionId
        ]);
    }
    
    /**
     * Ativa um empréstimo (quando as parcelas são criadas)
     */
    public function activateLoan($loanId)
    {
        return $this->update($loanId, [
            'status' => 'active'
        ]);
    }
    
    /**
     * Completa um empréstimo (quando todas as parcelas são pagas)
     */
    public function completeLoan($loanId)
    {
        return $this->update($loanId, [
            'status' => 'completed'
        ]);
    }
    
    /**
     * Cancela um empréstimo
     */
    public function cancelLoan($loanId)
    {
        return $this->update($loanId, [
            'status' => 'cancelled'
        ]);
    }
    
    /**
     * Marca empréstimo como inadimplente
     */
    public function defaultLoan($loanId)
    {
        return $this->update($loanId, [
            'status' => 'defaulted'
        ]);
    }
    
    /**
     * Estatísticas de empréstimos
     */
    public function getStatistics()
    {
        $stats = [];
        
        // Total de empréstimos
        $stats['total'] = $this->countAllResults();
        
        // Por status
        $statuses = ['pending_acceptance', 'accepted', 'pending_funding', 'funded', 'active', 'completed', 'cancelled', 'defaulted'];
        foreach ($statuses as $status) {
            $stats['by_status'][$status] = $this->where('status', $status)->countAllResults(false);
        }
        
        // Valor total emprestado (empréstimos ativos e completos)
        $result = $this->db->table($this->table . ' l')
            ->select('SUM(lp.loan_amount) as total_amount')
            ->join('loan_plans lp', 'lp.id = l.loan_plan_id')
            ->whereIn('l.status', ['active', 'completed'])
            ->get()
            ->getRowArray();
            
        $stats['total_amount_loaned'] = $result['total_amount'] ?? 0;
        
        return $stats;
    }
}