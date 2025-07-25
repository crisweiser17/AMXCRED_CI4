<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;
use App\Models\SettingModel;

class CpfApi extends BaseConfig
{
    /**
     * Timeout para requisições (em segundos)
     */
    public int $timeout = 30;

    /**
     * Retorna o token atual baseado no ambiente
     */
    public function getCurrentToken(): string
    {
        $settingModel = new SettingModel();
        $environment = $settingModel->getSetting('cpf_api', 'cpf_api_environment') ?? 'test';
        
        if ($environment === 'test') {
            return $settingModel->getSetting('cpf_api', 'cpf_api_test_token') ?? '5ae973d7a997af13f0aaf2bf60e65803';
        } else {
            return $settingModel->getSetting('cpf_api', 'cpf_api_production_token') ?? '';
        }
    }

    /**
     * Retorna a URL base atual baseada no ambiente
     */
    public function getCurrentBaseUrl(): string
    {
        $settingModel = new SettingModel();
        $environment = $settingModel->getSetting('cpf_api', 'cpf_api_environment') ?? 'test';
        
        if ($environment === 'test') {
            return $settingModel->getSetting('cpf_api', 'cpf_api_test_url') ?? 'https://api.cpfcnpj.com.br/test';
        } else {
            return $settingModel->getSetting('cpf_api', 'cpf_api_production_url') ?? 'https://api.cpfcnpj.com.br';
        }
    }

    /**
     * Verifica se está em ambiente de teste
     */
    public function isTestEnvironment(): bool
    {
        $settingModel = new SettingModel();
        return ($settingModel->getSetting('cpf_api', 'cpf_api_environment') ?? 'test') === 'test';
    }

    /**
     * Retorna o ambiente atual
     */
    public function getCurrentEnvironment(): string
    {
        $settingModel = new SettingModel();
        return $settingModel->getSetting('cpf_api', 'cpf_api_environment') ?? 'test';
    }
}