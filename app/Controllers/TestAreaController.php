<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LoanModel;
use App\Models\InstallmentModel;
use App\Models\ClientModel;
use App\Models\LoanPlanModel;

class TestAreaController extends BaseController
{
    protected $loanModel;
    protected $installmentModel;
    protected $clientModel;
    protected $loanPlanModel;

    public function __construct()
    {
        $this->loanModel = new LoanModel();
        $this->installmentModel = new InstallmentModel();
        $this->clientModel = new ClientModel();
        $this->loanPlanModel = new LoanPlanModel();
    }

    /**
     * Página principal da área de testes
     */
    public function index()
    {
        // Criar sessão de login automática para área de testes
        $session = session();
        if (!$session->get('is_logged_in')) {
            $session->set([
                'is_logged_in' => true,
                'user_id' => 1,
                'user_email' => 'admin@amxcred.com',
                'user_role' => 'admin'
            ]);
        }
        
        try {
            // Buscar empréstimos com dados do cliente
            $loans = $this->loanModel->select('loans.*, clients.full_name as client_name, clients.cpf as client_cpf, loan_plans.name as plan_name, loan_plans.loan_amount as amount')
                                    ->join('clients', 'clients.id = loans.client_id', 'left')
                                    ->join('loan_plans', 'loan_plans.id = loans.loan_plan_id', 'left')
                                    ->orderBy('loans.created_at', 'DESC')
                                    ->findAll();

            // Formatar dados dos empréstimos
            foreach ($loans as &$loan) {
                $loan['status_label'] = $this->getStatusLabel($loan['status']);
                $loan['status_class'] = $this->getStatusClass($loan['status']);
                $loan['created_at_formatted'] = date('d/m/Y H:i', strtotime($loan['created_at']));
            }

            $data = [
                'title' => 'Área de Testes',
                'loans' => $loans
            ];

            return view('settings/test_area', $data);
            
        } catch (\Exception $e) {
            log_message('error', 'Erro em TestAreaController::index: ' . $e->getMessage());
            return redirect()->to('/settings')->with('error', 'Erro ao carregar área de testes: ' . $e->getMessage());
        }
    }
    
    /**
     * Método de teste simples
     */
    public function test()
    {
        return 'Área de testes funcionando!';
    }
    
    /**
     * Método de debug para verificar se o controlador está sendo chamado
     */
    public function debug()
    {
        echo "<h1>Debug TestAreaController</h1>";
        echo "<p>Controlador está funcionando!</p>";
        echo "<p>URI: " . current_url() . "</p>";
        echo "<p>Método: " . $this->request->getMethod() . "</p>";
        echo "<p>Sessão ativa: " . (session()->get('is_logged_in') ? 'Sim' : 'Não') . "</p>";
        exit;
    }

    /**
     * Atualiza o status de um empréstimo
     */
    public function updateLoanStatus()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        try {
            $loanId = $this->request->getPost('loan_id');
            $newStatus = $this->request->getPost('status');

            if (!$loanId || !$newStatus) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Parâmetros obrigatórios não informados'
                ]);
            }

            // Verificar se o empréstimo existe
            $loan = $this->loanModel->find($loanId);
            if (!$loan) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Empréstimo não encontrado'
                ]);
            }

            // Validar status
            $validStatuses = ['pending_acceptance', 'accepted', 'pending_funding', 'funded', 'active', 'completed', 'cancelled', 'defaulted'];
            if (!in_array($newStatus, $validStatuses)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Status inválido'
                ]);
            }

            // Atualizar status
            $updateData = ['status' => $newStatus];
            
            // Adicionar campos específicos baseado no status
            switch ($newStatus) {
                case 'accepted':
                    $updateData['accepted_at'] = date('Y-m-d H:i:s');
                    break;
                case 'funded':
                    $updateData['funded_at'] = date('Y-m-d H:i:s');
                    $updateData['funded_by_user_id'] = 1; // ID do usuário de teste
                    break;
            }

            if ($this->loanModel->update($loanId, $updateData)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Status atualizado com sucesso!',
                    'new_status_label' => $this->getStatusLabel($newStatus),
                    'new_status_class' => $this->getStatusClass($newStatus)
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Erro ao atualizar status'
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'Erro em TestAreaController::updateLoanStatus: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erro interno: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Deleta um empréstimo
     */
    public function deleteLoan()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        try {
            $loanId = $this->request->getPost('loan_id');

            if (!$loanId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'ID do empréstimo não informado'
                ]);
            }

            // Verificar se o empréstimo existe
            $loan = $this->loanModel->find($loanId);
            if (!$loan) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Empréstimo não encontrado'
                ]);
            }

            // Deletar parcelas relacionadas primeiro
            $this->installmentModel->where('loan_id', $loanId)->delete();

            // Deletar o empréstimo
            if ($this->loanModel->delete($loanId)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Empréstimo deletado com sucesso!'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Erro ao deletar empréstimo'
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'Erro em TestAreaController::deleteLoan: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erro interno: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Busca parcelas de um empréstimo
     */
    public function getLoanInstallments($loanId)
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        try {
            $installments = $this->installmentModel->getInstallmentsByLoan($loanId);

            // Formatar dados das parcelas
            foreach ($installments as &$installment) {
                $installment['amount_formatted'] = 'R$ ' . number_format($installment['amount'], 2, ',', '.');
                $installment['due_date_formatted'] = date('d/m/Y', strtotime($installment['due_date']));
                $installment['status_label'] = $this->getInstallmentStatusLabel($installment['status']);
                $installment['status_class'] = $this->getInstallmentStatusClass($installment['status']);
                
                if ($installment['paid_at']) {
                    $installment['paid_at_formatted'] = date('d/m/Y H:i', strtotime($installment['paid_at']));
                }
            }

            return $this->response->setJSON([
                'success' => true,
                'installments' => $installments
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Erro em TestAreaController::getLoanInstallments: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erro ao buscar parcelas: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Marca uma parcela como paga
     */
    public function markInstallmentAsPaid()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        try {
            $installmentId = $this->request->getPost('installment_id');
            $paidDate = $this->request->getPost('paid_date');
            $paidAmount = $this->request->getPost('paid_amount');

            if (!$installmentId || !$paidDate || !$paidAmount) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Parâmetros obrigatórios não informados'
                ]);
            }

            // Verificar se a parcela existe
            $installment = $this->installmentModel->find($installmentId);
            if (!$installment) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Parcela não encontrada'
                ]);
            }

            // Validar data
            $paidDateTime = date('Y-m-d H:i:s', strtotime($paidDate . ' ' . date('H:i:s')));
            if (!$paidDateTime) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Data inválida'
                ]);
            }

            // Converter valor formatado para float
            $cleanAmount = str_replace(['R$', ' ', '.'], '', $paidAmount);
            $cleanAmount = str_replace(',', '.', $cleanAmount);
            
            // Atualizar parcela
            $updateData = [
                'status' => 'paid',
                'paid_at' => $paidDateTime,
                'amount' => (float) $cleanAmount
            ];

            if ($this->installmentModel->update($installmentId, $updateData)) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Parcela marcada como paga!'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Erro ao atualizar parcela'
                ]);
            }

        } catch (\Exception $e) {
            log_message('error', 'Erro em TestAreaController::markInstallmentAsPaid: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erro interno: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Retorna label do status do empréstimo
     */
    private function getStatusLabel($status)
    {
        $labels = [
            'pending_acceptance' => 'Aguardando Aceitação',
            'accepted' => 'Aceito',
            'pending_funding' => 'Aguardando Financiamento',
            'funded' => 'Financiado',
            'active' => 'Ativo',
            'completed' => 'Concluído',
            'cancelled' => 'Cancelado',
            'defaulted' => 'Inadimplente'
        ];

        return $labels[$status] ?? $status;
    }

    /**
     * Retorna classe CSS do status do empréstimo
     */
    private function getStatusClass($status)
    {
        $classes = [
            'pending_acceptance' => 'bg-yellow-100 text-yellow-800',
            'accepted' => 'bg-blue-100 text-blue-800',
            'pending_funding' => 'bg-orange-100 text-orange-800',
            'funded' => 'bg-purple-100 text-purple-800',
            'active' => 'bg-green-100 text-green-800',
            'completed' => 'bg-gray-100 text-gray-800',
            'cancelled' => 'bg-red-100 text-red-800',
            'defaulted' => 'bg-red-100 text-red-800'
        ];

        return $classes[$status] ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * Retorna label do status da parcela
     */
    private function getInstallmentStatusLabel($status)
    {
        $labels = [
            'pending' => 'Pendente',
            'paid' => 'Pago',
            'overdue' => 'Vencido',
            'cancelled' => 'Cancelado'
        ];

        return $labels[$status] ?? $status;
    }

    /**
     * Retorna classe CSS do status da parcela
     */
    private function getInstallmentStatusClass($status)
    {
        $classes = [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'paid' => 'bg-green-100 text-green-800',
            'overdue' => 'bg-red-100 text-red-800',
            'cancelled' => 'bg-gray-100 text-gray-800'
        ];

        return $classes[$status] ?? 'bg-gray-100 text-gray-800';
    }
}