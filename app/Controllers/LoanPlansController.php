<?php

namespace App\Controllers;

use App\Models\LoanPlanModel;
use CodeIgniter\Controller;

class LoanPlansController extends Controller
{
    protected $loanPlanModel;
    protected $validationErrors = [];

    public function __construct()
    {
        $this->loanPlanModel = new LoanPlanModel();
    }

    /**
     * Lista todos os planos de empréstimo
     */
    public function index()
    {
        try {
            $loanPlans = $this->loanPlanModel->orderBy('created_at', 'DESC')
                                           ->findAll();
            
            $data = [
                'title' => 'Planos de Empréstimo',
                'loanPlans' => $loanPlans
            ];

            return view('loan_plans/index', $data);
            
        } catch (\Exception $e) {
            log_message('error', 'Erro em LoanPlansController::index: ' . $e->getMessage());
            return redirect()->to('/dashboard')->with('error', 'Erro ao carregar planos: ' . $e->getMessage());
        }
    }

    /**
     * Exibe formulário para criar novo plano
     */
    public function create()
    {
        try {
            $data = [
                'title' => 'Novo Plano de Empréstimo',
                'errors' => session('errors') ?? []
            ];

            return view('loan_plans/create', $data);
            
        } catch (\Exception $e) {
            log_message('error', 'Erro em LoanPlansController::create: ' . $e->getMessage());
            return redirect()->to('/loan-plans')->with('error', 'Erro ao carregar formulário: ' . $e->getMessage());
        }
    }

    /**
     * Processa criação de novo plano
     */
    public function store()
    {
        try {
            $data = $this->request->getPost();
            
            // Validar dados
            if (!$this->validateLoanPlanData($data)) {
                return redirect()->back()
                                ->withInput()
                                ->with('errors', $this->validationErrors);
            }
            
            // Preparar dados para inserção
            $loanPlanData = [
                'name' => trim($data['name']),
                'loan_amount' => (float) $data['loan_amount'],
                'total_repayment_amount' => (float) $data['total_repayment_amount'],
                'number_of_installments' => (int) $data['number_of_installments'],
                'is_active' => isset($data['is_active']) ? 1 : 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            // Inserir no banco
            if ($this->loanPlanModel->insert($loanPlanData)) {
                return redirect()->to('/loan-plans')
                                ->with('success', 'Plano de empréstimo criado com sucesso!');
            } else {
                return redirect()->back()
                                ->withInput()
                                ->with('error', 'Erro ao criar plano de empréstimo.');
            }
            
        } catch (\Exception $e) {
            log_message('error', 'Erro em LoanPlansController::store: ' . $e->getMessage());
            return redirect()->back()
                            ->withInput()
                            ->with('error', 'Erro interno: ' . $e->getMessage());
        }
    }

    /**
     * Exibe formulário para editar plano
     */
    public function edit($id)
    {
        try {
            $loanPlan = $this->loanPlanModel->find($id);
            
            if (!$loanPlan) {
                return redirect()->to('/loan-plans')
                                ->with('error', 'Plano não encontrado.');
            }
            
            $data = [
                'title' => 'Editar Plano de Empréstimo',
                'loanPlan' => $loanPlan,
                'errors' => session('errors') ?? []
            ];

            return view('loan_plans/edit', $data);
            
        } catch (\Exception $e) {
            log_message('error', 'Erro em LoanPlansController::edit: ' . $e->getMessage());
            return redirect()->to('/loan-plans')->with('error', 'Erro ao carregar plano: ' . $e->getMessage());
        }
    }

    /**
     * Processa atualização do plano
     */
    public function update($id)
    {
        try {
            $loanPlan = $this->loanPlanModel->find($id);
            
            if (!$loanPlan) {
                return redirect()->to('/loan-plans')
                                ->with('error', 'Plano não encontrado.');
            }
            
            $data = $this->request->getPost();
            
            // Validar dados
            if (!$this->validateLoanPlanData($data, $id)) {
                return redirect()->back()
                                ->withInput()
                                ->with('errors', $this->validationErrors);
            }
            
            // Preparar dados para atualização
            $loanPlanData = [
                'name' => trim($data['name']),
                'loan_amount' => (float) $data['loan_amount'],
                'total_repayment_amount' => (float) $data['total_repayment_amount'],
                'number_of_installments' => (int) $data['number_of_installments'],
                'is_active' => isset($data['is_active']) ? 1 : 0,
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            // Atualizar no banco
            if ($this->loanPlanModel->update($id, $loanPlanData)) {
                return redirect()->to('/loan-plans')
                                ->with('success', 'Plano atualizado com sucesso!');
            } else {
                return redirect()->back()
                                ->withInput()
                                ->with('error', 'Erro ao atualizar plano.');
            }
            
        } catch (\Exception $e) {
            log_message('error', 'Erro em LoanPlansController::update: ' . $e->getMessage());
            return redirect()->back()
                            ->withInput()
                            ->with('error', 'Erro interno: ' . $e->getMessage());
        }
    }

    /**
     * Remove plano de empréstimo
     */
    public function delete($id)
    {
        try {
            $loanPlan = $this->loanPlanModel->find($id);
            
            if (!$loanPlan) {
                return redirect()->to('/loan-plans')
                                ->with('error', 'Plano não encontrado.');
            }
            
            // Verificar se há empréstimos usando este plano
            $loanModel = new \App\Models\LoanModel();
            $loansUsingPlan = $loanModel->where('loan_plan_id', $id)->countAllResults();
            
            if ($loansUsingPlan > 0) {
                return redirect()->to('/loan-plans')
                                ->with('error', 'Não é possível excluir este plano pois há empréstimos vinculados a ele.');
            }
            
            // Deletar plano
            if ($this->loanPlanModel->delete($id)) {
                return redirect()->to('/loan-plans')
                                ->with('success', 'Plano excluído com sucesso!');
            } else {
                return redirect()->to('/loan-plans')
                                ->with('error', 'Erro ao excluir plano.');
            }
            
        } catch (\Exception $e) {
            log_message('error', 'Erro em LoanPlansController::delete: ' . $e->getMessage());
            return redirect()->to('/loan-plans')
                            ->with('error', 'Erro interno: ' . $e->getMessage());
        }
    }

    /**
     * Valida dados do plano de empréstimo
     */
    private function validateLoanPlanData($data, $id = null)
    {
        $errors = [];
        
        // Validar nome
        if (empty($data['name']) || strlen(trim($data['name'])) < 3) {
            $errors['name'] = 'Nome do plano deve ter pelo menos 3 caracteres.';
        } else {
            // Verificar se nome já existe (exceto para o próprio registro na edição)
            $existingPlan = $this->loanPlanModel->where('name', trim($data['name']));
            if ($id) {
                $existingPlan = $existingPlan->where('id !=', $id);
            }
            $existingPlan = $existingPlan->first();
            
            if ($existingPlan) {
                $errors['name'] = 'Já existe um plano com este nome.';
            }
        }
        
        // Validar valor do empréstimo
        if (empty($data['loan_amount']) || !is_numeric($data['loan_amount']) || (float) $data['loan_amount'] <= 0) {
            $errors['loan_amount'] = 'Valor do empréstimo deve ser um número positivo.';
        }
        
        // Validar valor total de pagamento
        if (empty($data['total_repayment_amount']) || !is_numeric($data['total_repayment_amount']) || (float) $data['total_repayment_amount'] <= 0) {
            $errors['total_repayment_amount'] = 'Valor total de pagamento deve ser um número positivo.';
        }
        
        // Validar se valor total é maior que valor do empréstimo
        if (!empty($data['loan_amount']) && !empty($data['total_repayment_amount'])) {
            if ((float) $data['total_repayment_amount'] <= (float) $data['loan_amount']) {
                $errors['total_repayment_amount'] = 'Valor total de pagamento deve ser maior que o valor do empréstimo.';
            }
        }
        
        // Validar número de parcelas
        if (empty($data['number_of_installments']) || !is_numeric($data['number_of_installments']) || (int) $data['number_of_installments'] <= 0) {
            $errors['number_of_installments'] = 'Número de parcelas deve ser um número inteiro positivo.';
        }
        
        // Se há erros, armazenar e retornar false
        if (!empty($errors)) {
            $this->validationErrors = $errors;
            return false;
        }
        
        return true;
    }
}