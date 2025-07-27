<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'name', 'email', 'password', 'role', 'status', 
        'reset_token', 'reset_token_expires_at', 'last_login_at'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'name' => 'required|min_length[2]|max_length[255]',
        'email' => 'required|valid_email|is_unique[users.email,id,{id}]',
        'password' => 'required|min_length[6]',
        'role' => 'required|in_list[admin,manager,operator]',
        'status' => 'required|in_list[active,inactive]'
    ];
    
    protected $validationMessages = [
        'email' => [
            'is_unique' => 'Este email já está em uso.'
        ],
        'password' => [
            'min_length' => 'A senha deve ter pelo menos 6 caracteres.'
        ]
    ];
    
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = ['hashPassword'];
    protected $afterInsert    = [];
    protected $beforeUpdate   = ['hashPassword'];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
    
    /**
     * Hash password before insert/update
     */
    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }
        return $data;
    }
    
    /**
     * Verify user credentials
     */
    public function verifyCredentials(string $email, string $password): ?array
    {
        $user = $this->where('email', $email)
                     ->where('status', 'active')
                     ->first();
        
        if ($user && password_verify($password, $user['password'])) {
            // Update last login
            $this->update($user['id'], ['last_login_at' => date('Y-m-d H:i:s')]);
            return $user;
        }
        
        return null;
    }
    
    /**
     * Generate reset token
     */
    public function generateResetToken(string $email): ?string
    {
        $user = $this->where('email', $email)->first();
        
        if (!$user) {
            return null;
        }
        
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        $this->update($user['id'], [
            'reset_token' => $token,
            'reset_token_expires_at' => $expires
        ]);
        
        return $token;
    }
    
    /**
     * Reset password using token
     */
    public function resetPassword(string $token, string $newPassword): bool
    {
        $user = $this->where('reset_token', $token)
                     ->where('reset_token_expires_at >', date('Y-m-d H:i:s'))
                     ->first();
        
        if (!$user) {
            return false;
        }
        
        return $this->update($user['id'], [
            'password' => $newPassword, // Will be hashed by callback
            'reset_token' => null,
            'reset_token_expires_at' => null
        ]);
    }
}
