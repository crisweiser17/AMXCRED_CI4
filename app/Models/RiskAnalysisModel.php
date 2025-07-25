<?php

namespace App\Models;

use CodeIgniter\Model;

class RiskAnalysisModel extends Model
{
    protected $table = 'risk_analysis';
    protected $primaryKey = 'id';
    
    protected $useAutoIncrement = true;
    
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    
    protected $allowedFields = [
        'client_id', 'dividas_bancarias', 'cheque_sem_fundo', 
        'protesto_nacional', 'score', 'recomendacao_serasa', 'status'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = '';
    
    protected $validationRules = [
        'client_id' => 'required|integer',
        'status' => 'required|in_list[pendente,consultado]',
        'score' => 'permit_empty|integer|greater_than_equal_to[0]|less_than_equal_to[1000]'
    ];
    
    protected $validationMessages = [];
    
    /**
     * Busca a última análise de risco para um cliente
     */
    public function getLatestByClientId($clientId)
    {
        return $this->where('client_id', $clientId)
                    ->orderBy('created_at', 'DESC')
                    ->first();
    }
    
    /**
     * Verifica se o cliente tem análise de risco consultada
     */
    public function isConsultedByClientId($clientId)
    {
        $analysis = $this->getLatestByClientId($clientId);
        return $analysis && $analysis['status'] === 'consultado';
    }
    
    /**
     * Cria uma nova análise de risco
     */
    public function createAnalysis($clientId, $data)
    {
        $analysisData = [
            'client_id' => $clientId,
            'dividas_bancarias' => $data['dividas_bancarias'] ?? null,
            'cheque_sem_fundo' => $data['cheque_sem_fundo'] ?? null,
            'protesto_nacional' => $data['protesto_nacional'] ?? null,
            'score' => $data['score'] ?? null,
            'recomendacao_serasa' => $data['recomendacao_serasa'] ?? null,
            'status' => 'consultado'
        ];
        
        return $this->insert($analysisData);
    }
    
    /**
     * Atualiza uma análise de risco existente
     */
    public function updateAnalysis($id, $data)
    {
        $analysisData = [
            'dividas_bancarias' => $data['dividas_bancarias'] ?? null,
            'cheque_sem_fundo' => $data['cheque_sem_fundo'] ?? null,
            'protesto_nacional' => $data['protesto_nacional'] ?? null,
            'score' => $data['score'] ?? null,
            'recomendacao_serasa' => $data['recomendacao_serasa'] ?? null,
            'status' => 'consultado'
        ];
        
        return $this->update($id, $analysisData);
    }
    
    /**
     * Calcula o risco baseado nos dados
     */
    public function calculateRiskLevel($analysisData)
    {
        $riskFactors = 0;
        
        if ($analysisData['dividas_bancarias'] ?? false) {
            $riskFactors++;
        }
        
        if ($analysisData['cheque_sem_fundo'] ?? false) {
            $riskFactors++;
        }
        
        if ($analysisData['protesto_nacional'] ?? false) {
            $riskFactors++;
        }
        
        $score = $analysisData['score'] ?? 0;
        
        // Classificação de risco baseada no score e fatores
        if ($score >= 800 && $riskFactors === 0) {
            return 'baixo';
        } elseif ($score >= 600 && $riskFactors <= 1) {
            return 'medio';
        } else {
            return 'alto';
        }
    }
}