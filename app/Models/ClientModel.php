<?php
namespace App\Models;

use CodeIgniter\Model;

class ClientModel extends Model
{
    /**
     * Override update method to add debugging for PIX fields
     */
    public function update($id = null, $data = null): bool
    {
        // Debug: Log what data is being passed to the model
        log_message('debug', '=== ClientModel::update() ===');
        log_message('debug', 'ID: ' . $id);
        log_message('debug', 'Data recebida no model: ' . json_encode($data));
        
        if (is_array($data)) {
            log_message('debug', 'PIX data no model - Type: ' . ($data['pix_key_type'] ?? 'NULL') . ', Key: ' . ($data['pix_key'] ?? 'NULL'));
            log_message('debug', 'Employment duration data no model: ' . ($data['employment_duration'] ?? 'NULL'));
            
            // Verificar se os campos PIX estão nos allowedFields
            $pixFieldsAllowed = array_intersect(['pix_key_type', 'pix_key'], $this->allowedFields);
            log_message('debug', 'PIX fields permitidos: ' . json_encode($pixFieldsAllowed));
            
            // Verificar se employment_duration está nos allowedFields
            $employmentFieldAllowed = in_array('employment_duration', $this->allowedFields);
            log_message('debug', 'Employment duration field permitido: ' . ($employmentFieldAllowed ? 'TRUE' : 'FALSE'));
            
            // Remover campos que não estão nos allowedFields para debug
            $filteredData = array_intersect_key($data, array_flip($this->allowedFields));
            log_message('debug', 'Data após filtro allowedFields: ' . json_encode($filteredData));
        }
        
        // Chamar o método pai
        $result = parent::update($id, $data);
        
        log_message('debug', 'Resultado do parent::update(): ' . ($result ? 'TRUE' : 'FALSE'));
        
        // Se falhou, verificar erros de validação
        if (!$result) {
            $errors = $this->errors();
            log_message('debug', 'Erros de validação: ' . json_encode($errors));
            
            // Verificar se há erros no banco de dados
            $db = \Config\Database::connect();
            if ($db->error()['code'] !== 0) {
                log_message('debug', 'Erro do banco de dados: ' . json_encode($db->error()));
            }
        }
        
        log_message('debug', '=== FIM ClientModel::update() ===');
        
        return $result;
    }
    protected $table = 'clients';
    protected $primaryKey = 'id';
    
    protected $useAutoIncrement = true;
    
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    
    protected $allowedFields = [
        'full_name', 'cpf', 'email', 'phone', 'birth_date',
        'occupation', 'industry', 'employment_duration', 'monthly_income',
        'pix_key_type', 'pix_key',
        'zip_code', 'street', 'number', 'complement', 'neighborhood', 'city', 'state',
        'payslip_1', 'payslip_2', 'payslip_3', 'id_front', 'id_back', 'selfie'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = '';
    protected $deletedField = '';
    
    protected $validationRules = [];
    
    protected $validationMessages = [];
}