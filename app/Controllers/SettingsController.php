<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SettingModel;
use App\Models\LoanPlanModel;

class SettingsController extends BaseController
{
    protected $settingModel;
    protected $loanPlanModel;

    public function __construct()
    {
        $this->settingModel = new SettingModel();
        $this->loanPlanModel = new LoanPlanModel();
    }

    /**
     * Página principal de configurações
     */
    public function index()
    {
        $data = [
            'title' => 'Configurações do Sistema'
        ];

        return view('settings/index', $data);
    }

    /**
     * Configurações de campos obrigatórios
     */
    public function requiredFields()
    {
        $data = [
            'title' => 'Campos Obrigatórios',
            'fieldGroups' => $this->settingModel->getClientFieldsGrouped()
        ];

        return view('settings/required_fields', $data);
    }

    /**
     * Atualiza configurações de campos obrigatórios
     */
    public function updateRequiredFields()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back()->with('error', 'Requisição inválida');
        }

        $fields = $this->request->getJSON(true);
        
        if (empty($fields)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Nenhum campo foi enviado'
            ]);
        }

        // Garantir que campos sempre obrigatórios não sejam alterados
        $fields['full_name'] = true;
        $fields['cpf'] = true;

        try {
            $success = $this->settingModel->updateRequiredFields($fields);
            
            if ($success) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Configurações atualizadas com sucesso!'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Erro ao atualizar configurações'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erro interno: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Busca uma configuração específica via AJAX
     */
    public function getSetting()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        $category = $this->request->getGet('category');
        $key = $this->request->getGet('key');

        if (!$category || !$key) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Parâmetros obrigatórios não informados'
            ]);
        }

        $value = $this->settingModel->getSetting($category, $key);

        return $this->response->setJSON([
            'success' => true,
            'value' => $value
        ]);
    }

    /**
     * Define uma configuração via AJAX
     */
    public function setSetting()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back();
        }

        $data = $this->request->getJSON(true);
        
        if (!isset($data['category']) || !isset($data['key']) || !isset($data['value'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Parâmetros obrigatórios não informados'
            ]);
        }

        try {
            $success = $this->settingModel->setSetting(
                $data['category'],
                $data['key'],
                $data['value'],
                $data['description'] ?? null
            );

            if ($success) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Configuração salva com sucesso!'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Erro ao salvar configuração'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erro interno: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Configurações da API CPF
     */
    public function cpfApi()
    {
        // Buscar configurações atuais da API CPF
        $settings = [
            'cpf_api_environment' => $this->settingModel->getSetting('cpf_api', 'cpf_api_environment') ?? 'test',
            'cpf_api_test_token' => $this->settingModel->getSetting('cpf_api', 'cpf_api_test_token') ?? '',
            'cpf_api_production_token' => $this->settingModel->getSetting('cpf_api', 'cpf_api_production_token') ?? '',
            'cpf_api_test_url' => $this->settingModel->getSetting('cpf_api', 'cpf_api_test_url') ?? 'https://api.cpfcnpj.com.br/test',
            'cpf_api_production_url' => $this->settingModel->getSetting('cpf_api', 'cpf_api_production_url') ?? 'https://api.cpfcnpj.com.br'
        ];

        $data = [
            'title' => 'Configurações da API CPF',
            'settings' => $settings
        ];

        return view('settings/cpf_api', $data);
    }

    /**
     * Salva configurações da API CPF
     */
    public function saveCpfApi()
    {
        $settings = [
            'cpf_api_environment' => $this->request->getPost('cpf_api_environment'),
            'cpf_api_test_token' => $this->request->getPost('cpf_api_test_token'),
            'cpf_api_production_token' => $this->request->getPost('cpf_api_production_token'),
            'cpf_api_test_url' => $this->request->getPost('cpf_api_test_url'),
            'cpf_api_production_url' => $this->request->getPost('cpf_api_production_url')
        ];

        $success = true;
        foreach ($settings as $key => $value) {
            if (!$this->settingModel->setSetting('cpf_api', $key, $value)) {
                $success = false;
                break;
            }
        }

        return $this->response->setJSON([
            'success' => $success,
            'message' => $success ? 'Configurações salvas com sucesso!' : 'Erro ao salvar configurações'
        ]);
    }

    /**
     * Testa conexão com a API CPF
     */
    public function testCpfApi()
    {
        try {
            // Usar um CPF de teste conhecido
            $testCpf = '11144477735'; // CPF de teste padrão
            
            // Instanciar o ClientController para usar o método de consulta
            $clientController = new \App\Controllers\ClientController();
            
            // Usar reflection para acessar o método privado
            $reflection = new \ReflectionClass($clientController);
            $method = $reflection->getMethod('consultCpfApi');
            $method->setAccessible(true);
            
            // Executar a consulta
            $result = $method->invoke($clientController, $testCpf);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Conexão testada com sucesso! CPF consultado: ' . $testCpf,
                'data' => $result
            ]);
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erro na conexão: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Configurações de SMTP
     */
    public function smtp()
    {
        // Buscar configurações atuais de SMTP
        $settings = [
            'protocol' => $this->settingModel->getSetting('email', 'protocol') ?? 'mail',
            'fromEmail' => $this->settingModel->getSetting('email', 'fromEmail') ?? '',
            'fromName' => $this->settingModel->getSetting('email', 'fromName') ?? '',
            'SMTPHost' => $this->settingModel->getSetting('email', 'SMTPHost') ?? '',
            'SMTPUser' => $this->settingModel->getSetting('email', 'SMTPUser') ?? '',
            'SMTPPass' => $this->settingModel->getSetting('email', 'SMTPPass') ?? '',
            'SMTPPort' => $this->settingModel->getSetting('email', 'SMTPPort') ?? '587',
            'SMTPTimeout' => $this->settingModel->getSetting('email', 'SMTPTimeout') ?? '5',
            'SMTPKeepAlive' => $this->settingModel->getSetting('email', 'SMTPKeepAlive') ?? false,
            'SMTPCrypto' => $this->settingModel->getSetting('email', 'SMTPCrypto') ?? 'tls',
            'mailType' => $this->settingModel->getSetting('email', 'mailType') ?? 'text',
            'charset' => $this->settingModel->getSetting('email', 'charset') ?? 'UTF-8'
        ];

        $data = [
            'title' => 'Configurações SMTP',
            'settings' => $settings
        ];

        return view('settings/smtp', $data);
    }

    /**
     * Salva configurações SMTP
     */
    public function saveSmtp()
    {
        $settings = [
            'protocol' => $this->request->getPost('protocol'),
            'fromEmail' => $this->request->getPost('fromEmail'),
            'fromName' => $this->request->getPost('fromName'),
            'SMTPHost' => $this->request->getPost('SMTPHost'),
            'SMTPUser' => $this->request->getPost('SMTPUser'),
            'SMTPPass' => $this->request->getPost('SMTPPass'),
            'SMTPPort' => $this->request->getPost('SMTPPort'),
            'SMTPTimeout' => $this->request->getPost('SMTPTimeout'),
            'SMTPKeepAlive' => $this->request->getPost('SMTPKeepAlive') ? true : false,
            'SMTPCrypto' => $this->request->getPost('SMTPCrypto'),
            'mailType' => $this->request->getPost('mailType'),
            'charset' => $this->request->getPost('charset')
        ];

        $success = true;
        foreach ($settings as $key => $value) {
            if (!$this->settingModel->setSetting('email', $key, $value)) {
                $success = false;
                break;
            }
        }

        return $this->response->setJSON([
            'success' => $success,
            'message' => $success ? 'Configurações SMTP salvas com sucesso!' : 'Erro ao salvar configurações SMTP'
        ]);
    }

    /**
     * Testa envio de e-mail SMTP
     */
    public function testSmtp()
    {
        try {
            $input = $this->request->getJSON(true);
            $testEmail = $input['email'] ?? null;
            
            if (!$testEmail || !filter_var($testEmail, FILTER_VALIDATE_EMAIL)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'E-mail inválido para teste'
                ]);
            }

            // Carregar configurações atuais
            $emailConfig = new \Config\Email();
            
            // Aplicar configurações salvas
            $emailConfig->protocol = $this->settingModel->getSetting('email', 'protocol') ?? 'mail';
            $emailConfig->fromEmail = $this->settingModel->getSetting('email', 'fromEmail') ?? '';
            $emailConfig->fromName = $this->settingModel->getSetting('email', 'fromName') ?? '';
            $emailConfig->SMTPHost = $this->settingModel->getSetting('email', 'SMTPHost') ?? '';
            $emailConfig->SMTPUser = $this->settingModel->getSetting('email', 'SMTPUser') ?? '';
            $emailConfig->SMTPPass = $this->settingModel->getSetting('email', 'SMTPPass') ?? '';
            $emailConfig->SMTPPort = (int)($this->settingModel->getSetting('email', 'SMTPPort') ?? 587);
            $emailConfig->SMTPTimeout = (int)($this->settingModel->getSetting('email', 'SMTPTimeout') ?? 5);
            $emailConfig->SMTPKeepAlive = (bool)($this->settingModel->getSetting('email', 'SMTPKeepAlive') ?? false);
            $emailConfig->SMTPCrypto = $this->settingModel->getSetting('email', 'SMTPCrypto') ?? 'tls';
            $emailConfig->mailType = $this->settingModel->getSetting('email', 'mailType') ?? 'text';
            $emailConfig->charset = $this->settingModel->getSetting('email', 'charset') ?? 'UTF-8';

            // Inicializar serviço de e-mail
            $email = \Config\Services::email($emailConfig);
            
            $email->setFrom($emailConfig->fromEmail, $emailConfig->fromName);
            $email->setTo($testEmail);
            $email->setSubject('Teste de Configuração SMTP - Sistema AMX');
            
            if ($emailConfig->mailType === 'html') {
                $email->setMessage('<h2>Teste de E-mail</h2><p>Este é um e-mail de teste para verificar as configurações SMTP do sistema AMX.</p><p>Se você recebeu este e-mail, as configurações estão funcionando corretamente!</p>');
            } else {
                $email->setMessage('Teste de E-mail\n\nEste é um e-mail de teste para verificar as configurações SMTP do sistema AMX.\n\nSe você recebeu este e-mail, as configurações estão funcionando corretamente!');
            }

            if ($email->send()) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'E-mail de teste enviado com sucesso para ' . $testEmail
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Erro ao enviar e-mail: ' . $email->printDebugger(['headers'])
                ]);
            }
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erro ao enviar e-mail de teste: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Configurações de cores do sistema (placeholder para futuro)
     */
    public function colors()
    {
        $data = [
            'title' => 'Cores do Sistema'
        ];

        return view('settings/colors', $data);
    }

    /**
     * Configurações de pagamento (placeholder para futuro)
     */
    public function payment()
    {
        $data = [
            'title' => 'Integrações de Pagamento'
        ];

        return view('settings/payment', $data);
    }

    // ========================================
    // MÉTODOS PARA PLANOS DE EMPRÉSTIMO
    // ========================================

    /**
     * Lista todos os planos de empréstimo
     */
    public function loanPlans()
    {
        // Log de debug
        log_message('debug', 'loanPlans chamado');
        
        try {
            // Usar conexão direta ao banco para evitar problemas no modelo
            $db = \Config\Database::connect();
            
            // Query simples e direta
            $sql = "SELECT id, name, loan_amount, total_repayment_amount, number_of_installments, is_active, created_at, updated_at FROM loan_plans ORDER BY created_at DESC";
            $query = $db->query($sql);
            $plansData = $query->getResultArray();
            
            // Processar dados manualmente (sem usar o modelo problemático)
            $plans = [];
            foreach ($plansData as $planData) {
                $loanAmount = (float)$planData['loan_amount'];
                $totalAmount = (float)$planData['total_repayment_amount'];
                $installments = (int)$planData['number_of_installments'];
                
                // Cálculos seguros
                $installmentAmount = $installments > 0 ? ($totalAmount / $installments) : 0;
                $totalInterest = $totalAmount - $loanAmount;
                $monthlyRate = ($loanAmount > 0 && $installments > 0) ?
                              ((pow(($totalAmount / $loanAmount), (1 / $installments)) - 1) * 100) : 0;
                
                $plans[] = [
                    'id' => (int)$planData['id'],
                    'name' => htmlspecialchars($planData['name'], ENT_QUOTES, 'UTF-8'),
                    'loan_amount' => $loanAmount,
                    'total_repayment_amount' => $totalAmount,
                    'number_of_installments' => $installments,
                    'installment_amount' => round($installmentAmount, 2),
                    'total_interest' => round($totalInterest, 2),
                    'monthly_interest_rate' => round($monthlyRate, 2),
                    'is_active' => (bool)$planData['is_active'],
                    'created_at' => $planData['created_at'],
                    'updated_at' => $planData['updated_at']
                ];
            }
            
            log_message('debug', 'Dados dos planos preparados: ' . count($plans) . ' planos');
            
            $data = [
                'title' => 'Planos de Empréstimo',
                'plans' => $plans
            ];

            return view('settings/loan_plans', $data);
            
        } catch (\Exception $e) {
            log_message('error', 'Erro crítico em loanPlans: ' . $e->getMessage());
            
            $data = [
                'title' => 'Planos de Empréstimo',
                'plans' => []
            ];
            
            return view('settings/loan_plans', $data);
        }
    }

    /**
     * Exibe formulário para criar novo plano
     */
    public function createLoanPlan()
    {
        $data = [
            'title' => 'Novo Plano de Empréstimo',
            'errors' => session('errors') ?? []
        ];

        return view('settings/loan_plan_form', $data);
    }

    /**
     * Processa criação de novo plano
     */
    public function storeLoanPlan()
    {
        $data = $this->request->getPost();
        
        // Validação adicional customizada
        if (!$this->loanPlanModel->validateAmounts($data)) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'O valor total a pagar deve ser maior que o valor do empréstimo.');
        }

        if ($this->loanPlanModel->insert($data)) {
            return redirect()->to('/settings/loan-plans')
                           ->with('success', 'Plano de empréstimo criado com sucesso!');
        } else {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->loanPlanModel->errors())
                           ->with('error', 'Erro ao criar plano. Verifique os dados informados.');
        }
    }

    /**
     * Exibe formulário para editar plano
     */
    public function editLoanPlan($id)
    {
        // Log de debug
        log_message('debug', 'editLoanPlan chamado para ID: ' . $id);
        
        try {
            // Validação básica
            if (!is_numeric($id) || $id <= 0) {
                log_message('error', 'ID inválido: ' . $id);
                return redirect()->to('/settings/loan-plans')->with('error', 'ID inválido');
            }
            
            // Usar conexão direta ao banco para evitar problemas no modelo
            $db = \Config\Database::connect();
            
            // Query simples e direta
            $sql = "SELECT id, name, loan_amount, total_repayment_amount, number_of_installments, is_active, created_at, updated_at FROM loan_plans WHERE id = ? LIMIT 1";
            $query = $db->query($sql, [(int)$id]);
            $planData = $query->getRowArray();
            
            if (!$planData) {
                log_message('error', 'Plano não encontrado para ID: ' . $id);
                return redirect()->to('/settings/loan-plans')->with('error', 'Plano não encontrado');
            }

            // Sanitizar dados do plano
            $plan = [
                'id' => (int)$planData['id'],
                'name' => htmlspecialchars($planData['name'], ENT_QUOTES, 'UTF-8'),
                'loan_amount' => max(0, (float)$planData['loan_amount']),
                'total_repayment_amount' => max(0, (float)$planData['total_repayment_amount']),
                'number_of_installments' => max(1, min(120, (int)$planData['number_of_installments'])),
                'is_active' => (bool)$planData['is_active'],
                'created_at' => $planData['created_at'],
                'updated_at' => $planData['updated_at']
            ];
            
            log_message('debug', 'Dados do plano para edição preparados');

            $data = [
                'title' => 'Editar Plano de Empréstimo',
                'plan' => $plan,
                'errors' => session('errors') ?? []
            ];

            return view('settings/loan_plan_form', $data);
            
        } catch (\Exception $e) {
            log_message('error', 'Erro crítico em editLoanPlan: ' . $e->getMessage());
            return redirect()->to('/settings/loan-plans')->with('error', 'Erro interno do sistema');
        }
    }

    /**
     * Processa atualização de plano
     */
    public function updateLoanPlan($id)
    {
        $plan = $this->loanPlanModel->find($id);
        
        if (!$plan) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Plano não encontrado');
        }

        $data = $this->request->getPost();
        
        // Validação adicional customizada
        if (!$this->loanPlanModel->validateAmounts($data)) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'O valor total a pagar deve ser maior que o valor do empréstimo.');
        }

        // Definir regras de validação manualmente para resolver o problema do is_unique
        $validationRules = [
            'name' => "required|min_length[3]|max_length[100]|is_unique[loan_plans.name,id,{$id}]",
            'loan_amount' => 'required|decimal|greater_than[0]',
            'total_repayment_amount' => 'required|decimal|greater_than[0]',
            'number_of_installments' => 'required|integer|greater_than[0]',
            'is_active' => 'permit_empty|in_list[0,1]'
        ];
        
        $validationMessages = [
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
        
        // Validar os dados manualmente
        $validation = \Config\Services::validation();
        $validation->setRules($validationRules, $validationMessages);
        
        if (!$validation->run($data)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $validation->getErrors())
                           ->with('error', 'Erro ao atualizar plano. Verifique os dados informados.');
        }

        // Atualizar diretamente no banco para evitar problemas de validação do modelo
        $db = \Config\Database::connect();
        $result = $db->table('loan_plans')
                    ->where('id', $id)
                    ->update([
                        'name' => $data['name'],
                        'loan_amount' => $data['loan_amount'],
                        'total_repayment_amount' => $data['total_repayment_amount'],
                        'number_of_installments' => $data['number_of_installments'],
                        'is_active' => $data['is_active'] ?? 1,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
        
        if ($result) {
            return redirect()->to('/settings/loan-plans')
                           ->with('success', 'Plano atualizado com sucesso!');
        } else {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Erro ao atualizar plano no banco de dados.');
        }
    }

    /**
     * Visualiza detalhes de um plano
     */
    public function viewLoanPlan($id)
    {
        // Log de debug
        log_message('debug', 'viewLoanPlan chamado para ID: ' . $id);
        
        try {
            // Validação básica
            if (!is_numeric($id) || $id <= 0) {
                log_message('error', 'ID inválido: ' . $id);
                return redirect()->to('/settings/loan-plans')->with('error', 'ID inválido');
            }
            
            // Usar conexão direta ao banco para evitar problemas no modelo
            $db = \Config\Database::connect();
            
            // Query simples e direta
            $sql = "SELECT id, name, loan_amount, total_repayment_amount, number_of_installments, is_active, created_at, updated_at FROM loan_plans WHERE id = ? LIMIT 1";
            $query = $db->query($sql, [(int)$id]);
            $planData = $query->getRowArray();
            
            if (!$planData) {
                log_message('error', 'Plano não encontrado para ID: ' . $id);
                return redirect()->to('/settings/loan-plans')->with('error', 'Plano não encontrado');
            }
            
            // Cálculos manuais simples
            $loanAmount = (float)$planData['loan_amount'];
            $totalAmount = (float)$planData['total_repayment_amount'];
            $installments = (int)$planData['number_of_installments'];
            
            // Evitar divisão por zero
            $installmentAmount = $installments > 0 ? ($totalAmount / $installments) : 0;
            $totalInterest = $totalAmount - $loanAmount;
            $monthlyRate = ($loanAmount > 0 && $installments > 0) ?
                          ((pow(($totalAmount / $loanAmount), (1 / $installments)) - 1) * 100) : 0;
            
            // Dados seguros e validados
            $plan = [
                'id' => (int)$planData['id'],
                'name' => htmlspecialchars($planData['name'], ENT_QUOTES, 'UTF-8'),
                'loan_amount' => $loanAmount,
                'total_repayment_amount' => $totalAmount,
                'number_of_installments' => $installments,
                'installment_amount' => round($installmentAmount, 2),
                'total_interest' => round($totalInterest, 2),
                'monthly_interest_rate' => round($monthlyRate, 2),
                'is_active' => (bool)$planData['is_active'],
                'created_at' => $planData['created_at'],
                'updated_at' => $planData['updated_at']
            ];
            
            log_message('debug', 'Dados do plano preparados, carregando view');
            
            $data = [
                'title' => 'Detalhes do Plano',
                'plan' => $plan
            ];

            return view('settings/loan_plan_view_simple', $data);
            
        } catch (\Exception $e) {
            log_message('error', 'Erro crítico em viewLoanPlan: ' . $e->getMessage());
            return redirect()->to('/settings/loan-plans')->with('error', 'Erro interno do sistema');
        }
    }

    /**
     * Alterna status ativo/inativo de um plano via AJAX
     */
    public function toggleLoanPlanStatus($id)
    {
        log_message('debug', 'toggleLoanPlanStatus chamado para ID: ' . $id);
        
        if (!$this->request->isAJAX()) {
            log_message('error', 'Requisição não é AJAX');
            return redirect()->back()->with('error', 'Requisição inválida');
        }

        try {
            // Validação do ID
            if (!is_numeric($id) || $id <= 0) {
                log_message('error', 'ID inválido: ' . $id);
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'ID inválido'
                ]);
            }

            $plan = $this->loanPlanModel->find($id);
            log_message('debug', 'Plano encontrado: ' . ($plan ? 'sim' : 'não'));
            
            if (!$plan) {
                log_message('error', 'Plano não encontrado para ID: ' . $id);
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Plano não encontrado'
                ]);
            }

            log_message('debug', 'Status atual do plano: ' . ($plan['is_active'] ? 'ativo' : 'inativo'));
            
            // Tentar usar conexão direta ao banco para evitar problemas no modelo
            $db = \Config\Database::connect();
            $newStatus = $plan['is_active'] ? 0 : 1;
            
            log_message('debug', 'Tentando alterar status para: ' . ($newStatus ? 'ativo' : 'inativo'));
            
            $result = $db->query(
                "UPDATE loan_plans SET is_active = ?, updated_at = NOW() WHERE id = ?",
                [$newStatus, $id]
            );
            
            if ($result && $db->affectedRows() > 0) {
                $statusText = $newStatus ? 'ativado' : 'desativado';
                log_message('debug', 'Status alterado com sucesso para: ' . $statusText);
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => "Plano {$statusText} com sucesso!",
                    'new_status' => (bool)$newStatus
                ]);
            } else {
                log_message('error', 'Falha ao executar UPDATE. Affected rows: ' . $db->affectedRows());
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Erro ao alterar status do plano - nenhuma linha afetada'
                ]);
            }
            
        } catch (\Exception $e) {
            log_message('error', 'Erro em toggleLoanPlanStatus: ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erro interno: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Remove um plano (soft delete via is_active = false)
     */
    public function deleteLoanPlan($id)
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back()->with('error', 'Requisição inválida');
        }

        $plan = $this->loanPlanModel->find($id);
        
        if (!$plan) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Plano não encontrado'
            ]);
        }

        // Desativar o plano em vez de deletar
        if ($this->loanPlanModel->update($id, ['is_active' => false])) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'Plano removido com sucesso!'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erro ao remover plano'
            ]);
        }
    }

    /**
     * Configurações de Timezone
     */
    public function timezone()
    {
        // Buscar configuração atual do timezone
        $currentTimezone = $this->settingModel->getSetting('system', 'timezone') ?? 'America/Sao_Paulo';
        
        // Lista de timezones mais comuns no Brasil
        $timezones = [
            'America/Sao_Paulo' => 'São Paulo (UTC-3)',
            'America/Manaus' => 'Manaus (UTC-4)',
            'America/Rio_Branco' => 'Rio Branco (UTC-5)',
            'America/Noronha' => 'Fernando de Noronha (UTC-2)',
            'UTC' => 'UTC (Tempo Universal Coordenado)'
        ];

        $data = [
            'title' => 'Configurações de Timezone',
            'currentTimezone' => $currentTimezone,
            'timezones' => $timezones
        ];

        return view('settings/timezone', $data);
    }

    /**
     * Salva configurações de Timezone
     */
    public function saveTimezone()
    {
        $timezone = $this->request->getPost('timezone');
        
        if (empty($timezone)) {
            return redirect()->back()->with('error', 'Timezone é obrigatório');
        }
        
        // Validar se o timezone é válido
        if (!in_array($timezone, timezone_identifiers_list())) {
            return redirect()->back()->with('error', 'Timezone inválido');
        }
        
        try {
            // Salvar no banco de dados
            $success = $this->settingModel->setSetting('system', 'timezone', $timezone, 'Fuso horário do sistema');
            
            if ($success) {
                // Atualizar o timezone da aplicação imediatamente
                date_default_timezone_set($timezone);
                
                return redirect()->to('/settings/timezone')
                               ->with('success', 'Timezone atualizado com sucesso!');
            } else {
                return redirect()->back()
                               ->with('error', 'Erro ao salvar timezone');
            }
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Erro interno: ' . $e->getMessage());
        }
    }

    /**
     * Configurações de Mensagens do Sistema
     */
    public function systemMessages()
    {
        // Buscar mensagens atuais do sistema
        $messages = [
            'loan_terms_conditions' => $this->settingModel->getSetting('system_messages', 'loan_terms_conditions') ?? $this->getDefaultTermsAndConditions(),
            'loan_acceptance_success_title' => $this->settingModel->getSetting('system_messages', 'loan_acceptance_success_title'),
            'loan_acceptance_success_message' => $this->settingModel->getSetting('system_messages', 'loan_acceptance_success_message'),
            'loan_acceptance_success_next_steps' => $this->settingModel->getSetting('system_messages', 'loan_acceptance_success_next_steps'),
            'loan_rejection_success_title' => $this->settingModel->getSetting('system_messages', 'loan_rejection_success_title'),
            'loan_rejection_success_message' => $this->settingModel->getSetting('system_messages', 'loan_rejection_success_message'),
            'loan_acceptance_error_title' => $this->settingModel->getSetting('system_messages', 'loan_acceptance_error_title'),
            'loan_acceptance_error_message' => $this->settingModel->getSetting('system_messages', 'loan_acceptance_error_message')
        ];

        $data = [
            'title' => 'Mensagens do Sistema',
            'messages' => $messages
        ];

        return view('settings/system_messages', $data);
    }

    /**
     * Salva configurações de Mensagens do Sistema
     */
    public function saveSystemMessages()
    {
        $settings = [
            'loan_terms_conditions' => $this->request->getPost('loan_terms_conditions'),
            'loan_acceptance_success_title' => $this->request->getPost('loan_acceptance_success_title'),
            'loan_acceptance_success_message' => $this->request->getPost('loan_acceptance_success_message'),
            'loan_acceptance_success_next_steps' => $this->request->getPost('loan_acceptance_success_next_steps'),
            'loan_rejection_success_title' => $this->request->getPost('loan_rejection_success_title'),
            'loan_rejection_success_message' => $this->request->getPost('loan_rejection_success_message'),
            'loan_acceptance_error_title' => $this->request->getPost('loan_acceptance_error_title'),
            'loan_acceptance_error_message' => $this->request->getPost('loan_acceptance_error_message')
        ];
        
        try {
            $success = true;
            
            // Salvar todas as configurações
            foreach ($settings as $key => $value) {
                if ($value !== null) { // Só salva se o valor não for nulo
                    $description = $this->getSettingDescription($key);
                    if (!$this->settingModel->setSetting('system_messages', $key, $value, $description)) {
                        $success = false;
                        break;
                    }
                }
            }
            
            if ($success) {
                return redirect()->to('/settings/system-messages')
                               ->with('success', 'Mensagens atualizadas com sucesso!');
            } else {
                return redirect()->back()
                               ->with('error', 'Erro ao salvar mensagens');
            }
        } catch (\Exception $e) {
            return redirect()->back()
                           ->with('error', 'Erro interno: ' . $e->getMessage());
        }
    }
    
    /**
     * Retorna a descrição para cada configuração
     */
    private function getSettingDescription($key)
    {
        $descriptions = [
            'loan_terms_conditions' => 'Termos e condições exibidos na página de aceite de empréstimo',
            'loan_acceptance_success_title' => 'Título exibido na página de sucesso de aceitação de empréstimo',
            'loan_acceptance_success_message' => 'Mensagem exibida na página de sucesso de aceitação de empréstimo',
            'loan_acceptance_success_next_steps' => 'Próximos passos exibidos na página de sucesso de aceitação de empréstimo',
            'loan_rejection_success_title' => 'Título exibido na página de sucesso de recusa de empréstimo',
            'loan_rejection_success_message' => 'Mensagem exibida na página de sucesso de recusa de empréstimo',
            'loan_acceptance_error_title' => 'Título exibido na página de erro de aceitação de empréstimo',
            'loan_acceptance_error_message' => 'Mensagem exibida na página de erro de aceitação de empréstimo'
        ];
        
        return $descriptions[$key] ?? 'Configuração do sistema';
    }

    /**
     * Retorna os termos e condições padrão
     */
    private function getDefaultTermsAndConditions()
    {
        return '<ul class="space-y-2 text-sm text-gray-700">
            <li class="flex items-start">
                <span class="w-2 h-2 bg-blue-600 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                Ao aceitar este empréstimo, você concorda em pagar o valor total conforme especificado.
            </li>
            <li class="flex items-start">
                <span class="w-2 h-2 bg-blue-600 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                O valor de cada parcela será cobrado mensalmente.
            </li>
            <li class="flex items-start">
                <span class="w-2 h-2 bg-blue-600 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                O não pagamento das parcelas pode resultar em cobrança de juros e multas.
            </li>
            <li class="flex items-start">
                <span class="w-2 h-2 bg-blue-600 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                Você tem o direito de quitar antecipadamente o empréstimo.
            </li>
            <li class="flex items-start">
                <span class="w-2 h-2 bg-blue-600 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                Este empréstimo está sujeito às leis brasileiras de proteção ao consumidor.
            </li>
        </ul>';
    }

    /**
     * Configurações de Informações da Empresa
     */
    public function companyInfo()
    {
        // Buscar informações atuais da empresa
        $companyInfo = [
            'company_name' => $this->settingModel->getSetting('company_info', 'company_name') ?? 'AMX Cred',
            'company_slogan' => $this->settingModel->getSetting('company_info', 'company_slogan') ?? 'Empréstimos Rápidos e Seguros',
            'company_email' => $this->settingModel->getSetting('company_info', 'company_email') ?? 'contato@amxcred.com.br',
            'company_whatsapp' => $this->settingModel->getSetting('company_info', 'company_whatsapp') ?? '(11) 99999-9999',
            'company_phone' => $this->settingModel->getSetting('company_info', 'company_phone') ?? '(11) 3333-3333',
            'company_logo' => $this->settingModel->getSetting('company_info', 'company_logo') ?? ''
        ];

        $data = array_merge([
            'title' => 'Informações da Empresa'
        ], $companyInfo);

        return view('settings/company_info', $data);
    }

    /**
     * Salva configurações de Informações da Empresa
     */
    public function saveCompanyInfo()
    {
        if (!$this->request->isAJAX()) {
            return redirect()->back()->with('error', 'Requisição inválida');
        }

        $settings = [
            'company_name' => $this->request->getPost('company_name'),
            'company_slogan' => $this->request->getPost('company_slogan'),
            'company_email' => $this->request->getPost('company_email'),
            'company_whatsapp' => $this->request->getPost('company_whatsapp'),
            'company_phone' => $this->request->getPost('company_phone'),
            'company_logo' => $this->request->getPost('company_logo')
        ];
        
        // Validações básicas
        if (empty($settings['company_name'])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Nome da empresa é obrigatório'
            ]);
        }

        if (!empty($settings['company_email']) && !filter_var($settings['company_email'], FILTER_VALIDATE_EMAIL)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'E-mail inválido'
            ]);
        }

        if (!empty($settings['company_logo']) && !filter_var($settings['company_logo'], FILTER_VALIDATE_URL)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'URL do logo inválida'
            ]);
        }
        
        try {
            $success = true;
            
            // Salvar todas as configurações
            foreach ($settings as $key => $value) {
                $description = $this->getCompanyInfoDescription($key);
                if (!$this->settingModel->setSetting('company_info', $key, $value, $description)) {
                    $success = false;
                    break;
                }
            }
            
            if ($success) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Informações da empresa atualizadas com sucesso!'
                ]);
            } else {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Erro ao salvar informações da empresa'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erro interno: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Retorna a descrição para cada configuração de informações da empresa
     */
    private function getCompanyInfoDescription($key)
    {
        $descriptions = [
            'company_name' => 'Nome da empresa exibido no layout público',
            'company_slogan' => 'Slogan da empresa exibido no cabeçalho do layout público',
            'company_email' => 'E-mail de contato exibido no rodapé do layout público',
            'company_whatsapp' => 'Número do WhatsApp exibido no rodapé do layout público',
            'company_phone' => 'Telefone de contato exibido no rodapé do layout público',
            'company_logo' => 'URL do logo da empresa exibido no cabeçalho do layout público'
        ];
        
        return $descriptions[$key] ?? 'Configuração de informações da empresa';
    }
}