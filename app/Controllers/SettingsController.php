<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SettingModel;

class SettingsController extends BaseController
{
    protected $settingModel;

    public function __construct()
    {
        $this->settingModel = new SettingModel();
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
}