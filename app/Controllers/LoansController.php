<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LoanModel;
use App\Models\LoanPlanModel;
use App\Models\ClientModel;
use App\Models\InstallmentModel;
use App\Models\UserModel;

class LoansController extends BaseController
{
    protected $loanModel;
    protected $loanPlanModel;
    protected $clientModel;
    protected $installmentModel;
    protected $userModel;
    protected $validationErrors = [];

    public function __construct()
    {
        $this->loanModel = new LoanModel();
        $this->loanPlanModel = new LoanPlanModel();
        $this->clientModel = new ClientModel();
        $this->installmentModel = new InstallmentModel();
        $this->userModel = new UserModel();
    }

    /**
     * Lista todos os empréstimos
     */
    public function index()
    {
        try {
            // Capturar parâmetros de busca
            $search = $this->request->getGet('search') ?? '';
            $status = $this->request->getGet('status') ?? '';
            
            // Buscar empréstimos com detalhes usando query direta para evitar problemas
            $db = \Config\Database::connect();
            
            $sql = "SELECT l.*, c.full_name as client_name, c.cpf as client_cpf, c.email as client_email,
                           lp.name as plan_name, lp.loan_amount, lp.total_repayment_amount, lp.number_of_installments,
                           l.acceptance_token, l.token_expires_at
                    FROM loans l
                    LEFT JOIN clients c ON c.id = l.client_id
                    LEFT JOIN loan_plans lp ON lp.id = l.loan_plan_id
                    WHERE 1=1";
            
            $params = [];
            
            // Aplicar filtro de busca
            if (!empty($search)) {
                $sql .= " AND (c.full_name LIKE ? OR c.cpf LIKE ? OR l.id LIKE ?)";
                $searchParam = '%' . $search . '%';
                $params[] = $searchParam;
                $params[] = $searchParam;
                $params[] = $searchParam;
            }
            
            // Aplicar filtro de status
            if (!empty($status)) {
                $sql .= " AND l.status = ?";
                $params[] = $status;
            }
            
            $sql .= " ORDER BY l.created_at DESC";
            
            $query = $db->query($sql, $params);
            $loans = $query->getResultArray();
            
            // Processar dados para exibição
            foreach ($loans as &$loan) {
                $loan['status_label'] = $this->getStatusLabel($loan['status']);
                $loan['status_class'] = $this->getStatusClass($loan['status']);
                
                // Formatar datas
                if ($loan['created_at']) {
                    $loan['created_at_formatted'] = date('d/m/Y H:i', strtotime($loan['created_at']));
                }
                if ($loan['accepted_at']) {
                    $loan['accepted_at_formatted'] = date('d/m/Y H:i', strtotime($loan['accepted_at']));
                }
                if ($loan['funded_at']) {
                    $loan['funded_at_formatted'] = date('d/m/Y H:i', strtotime($loan['funded_at']));
                }
                
                // Formatar valores
                $loan['loan_amount_formatted'] = 'R$ ' . number_format($loan['loan_amount'], 2, ',', '.');
                $loan['total_repayment_amount_formatted'] = 'R$ ' . number_format($loan['total_repayment_amount'], 2, ',', '.');
            }
            
            $data = [
                'title' => 'Empréstimos',
                'loans' => $loans,
                'search' => $search,
                'status' => $status
            ];

            return view('loans/index', $data);
            
        } catch (\Exception $e) {
            log_message('error', 'Erro em LoansController::index: ' . $e->getMessage());
            
            $data = [
                'title' => 'Empréstimos',
                'loans' => [],
                'error' => 'Erro ao carregar empréstimos: ' . $e->getMessage()
            ];
            
            return view('loans/index', $data);
        }
    }

    /**
     * Exibe formulário para criar novo empréstimo
     */
    public function create()
    {
        try {
            // Buscar apenas clientes elegíveis para empréstimos
            $clients = $this->clientModel->getEligibleClients();
            
            // Buscar planos ativos
            $loanPlans = $this->loanPlanModel->where('is_active', 1)
                                            ->orderBy('name', 'ASC')
                                            ->findAll();
            
            // Verificar se há um cliente pré-selecionado via GET
            $preSelectedClientId = $this->request->getGet('client_id');
            $preSelectedClient = null;
            
            if ($preSelectedClientId) {
                // Verificar se o cliente existe e é elegível
                $preSelectedClient = $this->clientModel->find($preSelectedClientId);
                if ($preSelectedClient && $this->clientModel->isClientEligible($preSelectedClientId)) {
                    // Cliente válido e elegível
                } else {
                    // Cliente não encontrado ou não elegível
                    $preSelectedClient = null;
                    $preSelectedClientId = null;
                }
            }
            
            $data = [
                'title' => 'Novo Empréstimo',
                'clients' => $clients,
                'loanPlans' => $loanPlans,
                'preSelectedClientId' => $preSelectedClientId,
                'preSelectedClient' => $preSelectedClient,
                'errors' => session('errors') ?? []
            ];

            return view('loans/create', $data);
            
        } catch (\Exception $e) {
            log_message('error', 'Erro em LoansController::create: ' . $e->getMessage());
            return redirect()->to('/loans')->with('error', 'Erro ao carregar formulário: ' . $e->getMessage());
        }
    }

    /**
     * Processa criação de novo empréstimo
     */
    public function store()
    {
        try {
            $data = $this->request->getPost();
            
            // Validações adicionais
            if (!$this->validateLoanData($data)) {
                return redirect()->back()
                               ->withInput()
                               ->with('errors', $this->validationErrors)
                               ->with('error', 'Erro ao criar empréstimo. Verifique os dados informados.');
            }
            
            // Gerar token de aceitação
            $data['acceptance_token'] = $this->loanModel->generateAcceptanceToken();
            $data['token_expires_at'] = date('Y-m-d H:i:s', strtotime('+7 days')); // Token válido por 7 dias
            $data['status'] = 'pending_acceptance';
            
            if ($this->loanModel->insert($data)) {
                $loanId = $this->loanModel->getInsertID();
                
                // TODO: Enviar email/SMS para cliente com link de aceitação
                
                return redirect()->to('/loans/view/' . $loanId)
                               ->with('success', 'Empréstimo criado com sucesso! O cliente receberá um link para aceitar o empréstimo.');
            } else {
                return redirect()->back()
                               ->withInput()
                               ->with('errors', $this->loanModel->errors())
                               ->with('error', 'Erro ao criar empréstimo.');
            }
            
        } catch (\Exception $e) {
            log_message('error', 'Erro em LoansController::store: ' . $e->getMessage());
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Erro interno: ' . $e->getMessage());
        }
    }

    /**
     * Visualiza detalhes de um empréstimo
     */
    public function view($id)
    {
        try {
            if (!is_numeric($id) || $id <= 0) {
                return redirect()->to('/loans')->with('error', 'ID inválido');
            }
            
            // Buscar empréstimo com detalhes
            $loan = $this->loanModel->getLoanWithDetails($id);
            
            if (!$loan) {
                return redirect()->to('/loans')->with('error', 'Empréstimo não encontrado');
            }
            
            // Buscar parcelas do empréstimo
            $installments = $this->installmentModel->getInstallmentsByLoan($id);
            
            // Processar dados para exibição
            $loan['status_label'] = $this->getStatusLabel($loan['status']);
            $loan['status_class'] = $this->getStatusClass($loan['status']);
            
            // Formatar datas
            if ($loan['created_at']) {
                $loan['created_at_formatted'] = date('d/m/Y H:i', strtotime($loan['created_at']));
            }
            if ($loan['accepted_at']) {
                $loan['accepted_at_formatted'] = date('d/m/Y H:i', strtotime($loan['accepted_at']));
            }
            if ($loan['funded_at']) {
                $loan['funded_at_formatted'] = date('d/m/Y H:i', strtotime($loan['funded_at']));
            }
            
            // Formatar valores
            $loan['loan_amount_formatted'] = 'R$ ' . number_format($loan['loan_amount'], 2, ',', '.');
            $loan['total_repayment_amount_formatted'] = 'R$ ' . number_format($loan['total_repayment_amount'], 2, ',', '.');
            
            // Calcular valor da parcela
            if ($loan['number_of_installments'] > 0) {
                $installmentAmount = $loan['total_repayment_amount'] / $loan['number_of_installments'];
                $loan['installment_amount_formatted'] = 'R$ ' . number_format($installmentAmount, 2, ',', '.');
            }
            
            // Processar parcelas
            foreach ($installments as &$installment) {
                $installment['amount_formatted'] = 'R$ ' . number_format($installment['amount'], 2, ',', '.');
                $installment['due_date_formatted'] = date('d/m/Y', strtotime($installment['due_date']));
                $installment['status_label'] = $this->getInstallmentStatusLabel($installment['status']);
                $installment['status_class'] = $this->getInstallmentStatusClass($installment['status']);
                
                if ($installment['paid_at']) {
                    $installment['paid_at_formatted'] = date('d/m/Y H:i', strtotime($installment['paid_at']));
                }
            }
            
            $data = [
                'title' => 'Detalhes do Empréstimo #' . $loan['id'],
                'loan' => $loan,
                'installments' => $installments
            ];

            return view('loans/view', $data);
            
        } catch (\Exception $e) {
            log_message('error', 'Erro em LoansController::view: ' . $e->getMessage());
            return redirect()->to('/loans')->with('error', 'Erro ao carregar empréstimo: ' . $e->getMessage());
        }
    }

    /**
     * Financia um empréstimo
     */
    public function fund($id)
    {
        try {
            if (!is_numeric($id) || $id <= 0) {
                return redirect()->to('/loans')->with('error', 'ID inválido');
            }
            
            $loan = $this->loanModel->find($id);
            
            if (!$loan) {
                return redirect()->to('/loans')->with('error', 'Empréstimo não encontrado');
            }
            
            if ($loan['status'] !== 'accepted') {
                return redirect()->to('/loans/view/' . $id)
                               ->with('error', 'Empréstimo deve estar aceito para ser financiado.');
            }
            
            // Marcar como financiado
            $userId = session('user_id'); // Assumindo que há sessão de usuário
            $pixTransactionId = $this->request->getPost('pix_transaction_id');
            
            if ($this->loanModel->fundLoan($id, $userId, $pixTransactionId)) {
                // Buscar dados do plano para criar parcelas
                $loanPlan = $this->loanPlanModel->find($loan['loan_plan_id']);
                
                if ($loanPlan) {
                    // Criar parcelas
                    $this->installmentModel->createInstallmentsForLoan($id, $loanPlan);
                    
                    // Ativar empréstimo
                    $this->loanModel->activateLoan($id);
                }
                
                return redirect()->to('/loans/view/' . $id)
                               ->with('success', 'Empréstimo financiado com sucesso! As parcelas foram criadas.');
            } else {
                return redirect()->to('/loans/view/' . $id)
                               ->with('error', 'Erro ao financiar empréstimo.');
            }
            
        } catch (\Exception $e) {
            log_message('error', 'Erro em LoansController::fund: ' . $e->getMessage());
            return redirect()->to('/loans/view/' . $id)
                           ->with('error', 'Erro interno: ' . $e->getMessage());
        }
    }

    /**
     * Cancela um empréstimo
     */
    public function cancel($id)
    {
        try {
            if (!is_numeric($id) || $id <= 0) {
                return redirect()->to('/loans')->with('error', 'ID inválido');
            }
            
            $loan = $this->loanModel->find($id);
            
            if (!$loan) {
                return redirect()->to('/loans')->with('error', 'Empréstimo não encontrado');
            }
            
            if (in_array($loan['status'], ['completed', 'cancelled'])) {
                return redirect()->to('/loans/view/' . $id)
                               ->with('error', 'Empréstimo não pode ser cancelado.');
            }
            
            if ($this->loanModel->cancelLoan($id)) {
                // Cancelar parcelas pendentes
                $this->installmentModel->cancelInstallmentsByLoan($id);
                
                return redirect()->to('/loans/view/' . $id)
                               ->with('success', 'Empréstimo cancelado com sucesso.');
            } else {
                return redirect()->to('/loans/view/' . $id)
                               ->with('error', 'Erro ao cancelar empréstimo.');
            }
            
        } catch (\Exception $e) {
            log_message('error', 'Erro em LoansController::cancel: ' . $e->getMessage());
            return redirect()->to('/loans/view/' . $id)
                           ->with('error', 'Erro interno: ' . $e->getMessage());
        }
    }

    /**
     * Exibe página de confirmação do empréstimo (rota pública)
     */
    public function accept($token)
    {
        try {
            // Buscar empréstimo pelo token
            $loan = $this->loanModel->getLoanByToken($token);
            
            if (!$loan) {
                return redirect()->to('/loans/acceptance-error')
                               ->with('error', 'Token inválido ou expirado.');
            }
            
            // Verificar se o token não expirou
            if (strtotime($loan['token_expires_at']) < time()) {
                return redirect()->to('/loans/acceptance-error')
                               ->with('error', 'Link de aceitação expirado.');
            }
            
            // Verificar se o empréstimo ainda está aguardando aceitação
            if ($loan['status'] !== 'pending_acceptance') {
                if ($loan['status'] === 'accepted') {
                    return redirect()->to('/loans/acceptance-success')
                                   ->with('info', 'Este empréstimo já foi aceito anteriormente.');
                } else {
                    return redirect()->to('/loans/acceptance-error')
                                   ->with('error', 'Este empréstimo não está mais disponível para aceitação.');
                }
            }
            
            // Formatar dados para exibição
            $loan['loan_amount_formatted'] = 'R$ ' . number_format($loan['loan_amount'], 2, ',', '.');
            $loan['total_repayment_amount_formatted'] = 'R$ ' . number_format($loan['total_repayment_amount'], 2, ',', '.');
            $loan['installment_amount'] = $loan['total_repayment_amount'] / $loan['number_of_installments'];
            $loan['installment_amount_formatted'] = 'R$ ' . number_format($loan['installment_amount'], 2, ',', '.');
            $loan['token_expires_at_formatted'] = date('d/m/Y H:i', strtotime($loan['token_expires_at']));
            
            $data = [
                'title' => 'Aceitar Empréstimo - AMX Cred',
                'loan' => $loan,
                'token' => $token
            ];
            
            return view('loans/accept_confirmation', $data);
            
        } catch (\Exception $e) {
            log_message('error', 'Erro em LoansController::accept: ' . $e->getMessage());
            return redirect()->to('/loans/acceptance-error')
                           ->with('error', 'Erro interno: ' . $e->getMessage());
        }
    }
    
    /**
     * Processa a aceitação do empréstimo
     */
    public function processAcceptance()
    {
        try {
            $token = $this->request->getPost('token');
            $action = $this->request->getPost('action'); // 'accept' ou 'reject'
            
            if (!$token || !$action) {
                return redirect()->to('/loans/acceptance-error')
                               ->with('error', 'Dados inválidos.');
            }
            
            if ($action === 'accept') {
                if ($this->loanModel->acceptLoan($token)) {
                    return redirect()->to('/loans/acceptance-success')
                                   ->with('success', 'Empréstimo aceito com sucesso! Aguarde o financiamento.');
                } else {
                    return redirect()->to('/loans/acceptance-error')
                                   ->with('error', 'Erro ao aceitar empréstimo. Token pode estar inválido ou expirado.');
                }
            } elseif ($action === 'reject') {
                if ($this->loanModel->rejectLoan($token)) {
                    return redirect()->to('/loans/acceptance-success')
                                   ->with('info', 'Empréstimo recusado. Obrigado pelo seu tempo.');
                } else {
                    return redirect()->to('/loans/acceptance-error')
                                   ->with('error', 'Erro ao recusar empréstimo.');
                }
            }
            
            return redirect()->to('/loans/acceptance-error')
                           ->with('error', 'Ação inválida.');
            
        } catch (\Exception $e) {
            log_message('error', 'Erro em LoansController::processAcceptance: ' . $e->getMessage());
            return redirect()->to('/loans/acceptance-error')
                           ->with('error', 'Erro interno: ' . $e->getMessage());
        }
    }

    /**
     * Página de sucesso na aceitação
     */
    public function acceptanceSuccess()
    {
        return view('loans/acceptance_success');
    }

    /**
     * Página de erro na aceitação
     */
    public function acceptanceError()
    {
        return view('loans/acceptance_error');
    }
    
    /**
     * Envia notificação com link de aceitação para o cliente
     */
    public function sendNotification($loanId)
    {
        try {
            // Verificar se é uma requisição AJAX
            if (!$this->request->isAJAX()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Requisição inválida'
                ]);
            }
            
            // Buscar empréstimo
            $loan = $this->loanModel->getLoanWithDetails($loanId);
            
            if (!$loan) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Empréstimo não encontrado'
                ]);
            }
            
            // Verificar se o empréstimo está aguardando aceitação
            if ($loan['status'] !== 'pending_acceptance') {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Empréstimo não está aguardando aceitação'
                ]);
            }
            
            // Verificar se tem token válido
            if (empty($loan['acceptance_token'])) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Token de aceitação não encontrado'
                ]);
            }
            
            // Verificar se o token não expirou
            if (strtotime($loan['token_expires_at']) < time()) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Token de aceitação expirado'
                ]);
            }
            
            // Gerar link de aceitação
            $acceptanceUrl = base_url('/accept-loan/' . $loan['acceptance_token']);
            
            // TODO: Implementar envio por SMTP
            $emailSent = $this->sendEmailNotification($loan, $acceptanceUrl);
            
            // TODO: Implementar envio por WhatsApp no futuro
            // $whatsappSent = $this->sendWhatsAppNotification($loan, $acceptanceUrl);
            
            if ($emailSent) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Notificação enviada com sucesso por email!'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Erro ao enviar notificação por email'
                ]);
            }
            
        } catch (\Exception $e) {
            log_message('error', 'Erro em LoansController::sendNotification: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erro interno: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Envia notificação por email
     */
    private function sendEmailNotification($loan, $acceptanceUrl)
    {
        try {
            // Carregar biblioteca de email
            $email = \Config\Services::email();
            
            // Configurar email
            $email->setFrom('noreply@amxcred.com', 'AMXCred');
            $email->setTo($loan['client_email']);
            $email->setSubject('Aceite seu empréstimo - AMXCred');
            
            // Corpo do email
            $message = "
            <html>
            <head>
                <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                    .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                    .header { background-color: #2563eb; color: white; padding: 20px; text-align: center; }
                    .content { padding: 20px; background-color: #f9fafb; }
                    .button { display: inline-block; background-color: #10b981; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; margin: 20px 0; }
                    .footer { text-align: center; padding: 20px; font-size: 12px; color: #666; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h1>AMXCred</h1>
                        <h2>Seu empréstimo está pronto!</h2>
                    </div>
                    <div class='content'>
                        <p>Olá <strong>{$loan['client_name']}</strong>,</p>
                        <p>Seu empréstimo foi aprovado e está aguardando sua aceitação.</p>
                        
                        <h3>Detalhes do Empréstimo:</h3>
                        <ul>
                            <li><strong>Valor:</strong> R$ " . number_format($loan['loan_amount'], 2, ',', '.') . "</li>
                            <li><strong>Total a pagar:</strong> R$ " . number_format($loan['total_repayment_amount'], 2, ',', '.') . "</li>
                            <li><strong>Parcelas:</strong> {$loan['number_of_installments']}x</li>
                            <li><strong>Plano:</strong> {$loan['plan_name']}</li>
                        </ul>
                        
                        <p>Para aceitar seu empréstimo, clique no botão abaixo:</p>
                        <p style='text-align: center;'>
                            <a href='{$acceptanceUrl}' class='button'>ACEITAR EMPRÉSTIMO</a>
                        </p>
                        
                        <p><strong>Importante:</strong> Este link expira em " . date('d/m/Y H:i', strtotime($loan['token_expires_at'])) . "</p>
                        
                        <p>Se você não conseguir clicar no botão, copie e cole o link abaixo no seu navegador:</p>
                        <p style='word-break: break-all; background-color: #e5e7eb; padding: 10px; border-radius: 4px;'>{$acceptanceUrl}</p>
                    </div>
                    <div class='footer'>
                        <p>AMXCred - Soluções Financeiras</p>
                        <p>Este é um email automático, não responda.</p>
                    </div>
                </div>
            </body>
            </html>
            ";
            
            $email->setMessage($message);
            
            // Enviar email
            if ($email->send()) {
                log_message('info', 'Email de aceitação enviado para: ' . $loan['client_email']);
                return true;
            } else {
                log_message('error', 'Erro ao enviar email: ' . $email->printDebugger());
                return false;
            }
            
        } catch (\Exception $e) {
            log_message('error', 'Erro ao enviar email: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Valida dados do empréstimo
     */
    private function validateLoanData($data)
    {
        $errors = [];
        
        // Verificar se cliente existe
        if (!isset($data['client_id']) || !$this->clientModel->find($data['client_id'])) {
            $errors['client_id'] = 'Cliente não encontrado.';
        } else {
            // Verificar se cliente é elegível para empréstimos
            if (!$this->clientModel->isClientEligible($data['client_id'])) {
                $errors['client_id'] = 'Cliente não é elegível para empréstimos. É necessário ter documentos aprovados e consulta de CPF aprovada.';
            }
        }
        
        // Verificar se plano existe e está ativo
        if (!isset($data['loan_plan_id'])) {
            $errors['loan_plan_id'] = 'Plano de empréstimo é obrigatório.';
        } else {
            $loanPlan = $this->loanPlanModel->find($data['loan_plan_id']);
            if (!$loanPlan || !$loanPlan['is_active']) {
                $errors['loan_plan_id'] = 'Plano de empréstimo não encontrado ou inativo.';
            }
        }
        
        // Verificar se cliente já tem empréstimo ativo
        if (isset($data['client_id'])) {
            $existingLoan = $this->loanModel->where('client_id', $data['client_id'])
                                           ->whereIn('status', ['pending_acceptance', 'accepted', 'pending_funding', 'funded', 'active'])
                                           ->first();
            
            if ($existingLoan) {
                $errors['client_id'] = 'Cliente já possui um empréstimo ativo.';
            }
        }
        
        // Se há erros, armazenar e retornar false
        if (!empty($errors)) {
            $this->validationErrors = $errors;
            return false;
        }
        
        return true;
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