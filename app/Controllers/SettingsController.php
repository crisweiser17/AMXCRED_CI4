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