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
    
    /**
     * Busca clientes elegíveis para empréstimos
     * Um cliente é elegível se tem verificação visual aprovada E consulta CPF aprovada
     */
    public function getEligibleClients()
    {
        $db = \Config\Database::connect();
        
        // Query para buscar clientes com verificação visual e CPF aprovados
        $sql = "SELECT DISTINCT c.*
                FROM clients c
                LEFT JOIN cpf_consultation cc ON cc.client_id = c.id
                WHERE c.id_front IS NOT NULL 
                  AND c.id_back IS NOT NULL 
                  AND c.selfie IS NOT NULL
                  AND cc.id = (
                      SELECT cc2.id 
                      FROM cpf_consultation cc2 
                      WHERE cc2.client_id = c.id 
                      ORDER BY cc2.created_at DESC 
                      LIMIT 1
                  )
                  AND cc.status = 'aprovado'
                ORDER BY c.full_name ASC";
        
        $query = $db->query($sql);
        return $query->getResultArray();
    }
    
    /**
     * Verifica se um cliente específico é elegível
     */
    public function isClientEligible($clientId)
    {
        $db = \Config\Database::connect();
        
        // Verificar se tem documentos visuais
        $client = $this->find($clientId);
        if (!$client || !$client['id_front'] || !$client['id_back'] || !$client['selfie']) {
            return false;
        }
        
        // Verificar se tem consulta CPF aprovada
        $sql = "SELECT status 
                FROM cpf_consultation 
                WHERE client_id = ? 
                ORDER BY created_at DESC 
                LIMIT 1";
        
        $query = $db->query($sql, [$clientId]);
        $consultation = $query->getRowArray();
        
        return $consultation && $consultation['status'] === 'aprovado';
    }
    
    /**
     * Busca clientes com filtros, busca e paginação
     */
    public function getClientsWithFilters($filters = [])
    {
        $db = \Config\Database::connect();
        
        // Parâmetros padrão
        $search = $filters['search'] ?? '';
        $eligibility = $filters['eligibility'] ?? 'all';
        $dateFrom = $filters['date_from'] ?? '';
        $dateTo = $filters['date_to'] ?? '';
        $orderBy = $filters['order_by'] ?? 'full_name';
        $orderDir = $filters['order_dir'] ?? 'asc';
        $page = $filters['page'] ?? 1;
        $perPage = $filters['per_page'] ?? 20;
        
        // Construir query base
        $sql = "SELECT c.*, 
                       CASE 
                           WHEN c.id_front IS NOT NULL 
                                AND c.id_back IS NOT NULL 
                                AND c.selfie IS NOT NULL 
                                AND cc.status = 'aprovado' 
                           THEN 1 
                           ELSE 0 
                       END as is_eligible
                FROM clients c
                LEFT JOIN (
                    SELECT client_id, status,
                           ROW_NUMBER() OVER (PARTITION BY client_id ORDER BY created_at DESC) as rn
                    FROM cpf_consultation
                ) cc ON cc.client_id = c.id AND cc.rn = 1
                WHERE 1=1";
        
        $params = [];
        
        // Filtro de busca
        if (!empty($search)) {
            $sql .= " AND (c.full_name LIKE ? OR c.cpf LIKE ? OR c.email LIKE ? OR c.phone LIKE ?)";
            $searchParam = '%' . $search . '%';
            $params[] = $searchParam;
            $params[] = $searchParam;
            $params[] = $searchParam;
            $params[] = $searchParam;
        }
        
        // Filtro de elegibilidade
        if ($eligibility === 'eligible') {
            $sql .= " AND c.id_front IS NOT NULL AND c.id_back IS NOT NULL AND c.selfie IS NOT NULL AND cc.status = 'aprovado'";
        } elseif ($eligibility === 'not_eligible') {
            $sql .= " AND (c.id_front IS NULL OR c.id_back IS NULL OR c.selfie IS NULL OR cc.status != 'aprovado' OR cc.status IS NULL)";
        }
        
        // Filtro de data
        if (!empty($dateFrom)) {
            $sql .= " AND DATE(c.created_at) >= ?";
            $params[] = $dateFrom;
        }
        
        if (!empty($dateTo)) {
            $sql .= " AND DATE(c.created_at) <= ?";
            $params[] = $dateTo;
        }
        
        // Contar total de registros
        $countSql = "SELECT COUNT(*) as total FROM (" . $sql . ") as count_query";
        $countQuery = $db->query($countSql, $params);
        $totalRecords = $countQuery->getRowArray()['total'];
        
        // Ordenação
        $allowedOrderBy = ['full_name', 'cpf', 'created_at', 'is_eligible'];
        if (!in_array($orderBy, $allowedOrderBy)) {
            $orderBy = 'full_name';
        }
        
        $orderDir = strtoupper($orderDir) === 'DESC' ? 'DESC' : 'ASC';
        $sql .= " ORDER BY " . $orderBy . " " . $orderDir;
        
        // Paginação
        $offset = ($page - 1) * $perPage;
        $sql .= " LIMIT ? OFFSET ?";
        $params[] = $perPage;
        $params[] = $offset;
        
        // Executar query
        $query = $db->query($sql, $params);
        $clients = $query->getResultArray();
        
        // Calcular informações de paginação
        $totalPages = ceil($totalRecords / $perPage);
        
        return [
            'data' => $clients,
            'pagination' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total_records' => $totalRecords,
                'total_pages' => $totalPages,
                'has_previous' => $page > 1,
                'has_next' => $page < $totalPages
            ]
        ];
    }
}