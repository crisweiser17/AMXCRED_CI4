<?php
namespace App\Models;

use CodeIgniter\Model;

class ClientModel extends Model
{
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