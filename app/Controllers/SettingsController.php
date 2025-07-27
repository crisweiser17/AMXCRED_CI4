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
     * Configurações de SMTP (placeholder para futuro)
     */
    public function smtp()
    {
        $data = [
            'title' => 'Configurações SMTP'
        ];

        return view('settings/smtp', $data);
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
}