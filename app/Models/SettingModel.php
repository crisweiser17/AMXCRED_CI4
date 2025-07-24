<?php
namespace App\Models;

use CodeIgniter\Model;

class SettingModel extends Model
{
    protected $table = 'settings';
    protected $primaryKey = 'id';
    
    protected $useAutoIncrement = true;
    
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    
    protected $allowedFields = ['category', 'key', 'value', 'description'];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = '';
    
    protected $validationRules = [
        'category' => 'required|max_length[100]',
        'key' => 'required|max_length[100]',
        'value' => 'permit_empty',
        'description' => 'permit_empty'
    ];
    
    protected $validationMessages = [
        'category' => [
            'required' => 'A categoria é obrigatória',
            'max_length' => 'A categoria deve ter no máximo 100 caracteres'
        ],
        'key' => [
            'required' => 'A chave é obrigatória',
            'max_length' => 'A chave deve ter no máximo 100 caracteres'
        ]
    ];

    /**
     * Busca configurações por categoria
     */
    public function getByCategory($category)
    {
        return $this->where('category', $category)->findAll();
    }

    /**
     * Busca uma configuração específica
     */
    public function getSetting($category, $key, $default = null)
    {
        $setting = $this->where('category', $category)
                       ->where('key', $key)
                       ->first();
        
        return $setting ? $setting['value'] : $default;
    }

    /**
     * Define uma configuração
     */
    public function setSetting($category, $key, $value, $description = null)
    {
        $existing = $this->where('category', $category)
                        ->where('key', $key)
                        ->first();
        
        $data = [
            'category' => $category,
            'key' => $key,
            'value' => $value,
            'description' => $description
        ];
        
        if ($existing) {
            return $this->update($existing['id'], $data);
        } else {
            return $this->insert($data);
        }
    }

    /**
     * Busca campos obrigatórios para clientes
     */
    public function getRequiredClientFields()
    {
        $settings = $this->getByCategory('client_required_fields');
        $required = [];
        
        foreach ($settings as $setting) {
            if ($setting['value'] === 'true') {
                $required[] = $setting['key'];
            }
        }
        
        return $required;
    }

    /**
     * Atualiza configurações de campos obrigatórios em lote
     */
    public function updateRequiredFields($fields)
    {
        $this->db->transStart();
        
        foreach ($fields as $field => $required) {
            $this->setSetting('client_required_fields', $field, $required ? 'true' : 'false');
        }
        
        $this->db->transComplete();
        
        return $this->db->transStatus();
    }

    /**
     * Busca configurações organizadas por seções para a interface
     */
    public function getClientFieldsGrouped()
    {
        $settings = $this->getByCategory('client_required_fields');
        
        $groups = [
            'personal' => [
                'title' => 'Dados Pessoais',
                'fields' => []
            ],
            'professional' => [
                'title' => 'Dados Profissionais',
                'fields' => []
            ],
            'pix' => [
                'title' => 'Dados PIX',
                'fields' => []
            ],
            'address' => [
                'title' => 'Endereço',
                'fields' => []
            ],
            'documents' => [
                'title' => 'Documentos',
                'fields' => []
            ]
        ];
        
        $fieldGroups = [
            'personal' => ['full_name', 'cpf', 'email', 'phone', 'birth_date'],
            'professional' => ['occupation', 'industry', 'employment_duration', 'monthly_income'],
            'pix' => ['pix_key_type', 'pix_key'],
            'address' => ['zip_code', 'street', 'number', 'complement', 'neighborhood', 'city', 'state'],
            'documents' => ['payslip_1', 'payslip_2', 'payslip_3', 'id_front', 'id_back', 'selfie']
        ];
        
        foreach ($settings as $setting) {
            foreach ($fieldGroups as $groupKey => $fields) {
                if (in_array($setting['key'], $fields)) {
                    $groups[$groupKey]['fields'][] = [
                        'key' => $setting['key'],
                        'description' => $setting['description'],
                        'required' => $setting['value'] === 'true',
                        'locked' => in_array($setting['key'], ['full_name', 'cpf']) // Campos sempre obrigatórios
                    ];
                    break;
                }
            }
        }
        
        return $groups;
    }
}