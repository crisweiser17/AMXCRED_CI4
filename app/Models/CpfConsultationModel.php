<?php

namespace App\Models;

use CodeIgniter\Model;

class CpfConsultationModel extends Model
{
    protected $table = 'cpf_consultation';
    protected $primaryKey = 'id';
    
    protected $useAutoIncrement = true;
    
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    
    protected $allowedFields = [
        'client_id', 'raw_json', 'cpf_valido', 'cpf_regular',
        'dados_divergentes', 'obito', 'ano_obito', 'codigo_erro',
        'mensagem_erro', 'status', 'motivo_reprovacao'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = '';
    
    protected $validationRules = [
        'client_id' => 'required|integer',
        'status' => 'required|in_list[pendente,aprovado,reprovado]'
    ];
    
    protected $validationMessages = [];
    
    /**
     * Busca a última consulta de CPF para um cliente
     */
    public function getLatestByClientId($clientId)
    {
        return $this->where('client_id', $clientId)
                    ->orderBy('created_at', 'DESC')
                    ->first();
    }
    
    /**
     * Verifica se o cliente tem consulta de CPF aprovada
     */
    public function isApprovedByClientId($clientId)
    {
        $consultation = $this->getLatestByClientId($clientId);
        return $consultation && $consultation['status'] === 'aprovado';
    }
    
    /**
     * Cria uma nova consulta de CPF
     */
    public function createConsultation($clientId, $apiData)
    {
        $data = [
            'client_id' => $clientId,
            'raw_json' => json_encode($apiData),
            'cpf_valido' => $apiData['cpf_valido'] ?? false,
            'cpf_regular' => $apiData['cpf_regular'] ?? false,
            'dados_divergentes' => $apiData['dados_divergentes'] ?? false,
            'obito' => $apiData['obito'] ?? false,
            'status' => $this->determineStatus($apiData),
            'motivo_reprovacao' => $this->determineRejectionReason($apiData)
        ];
        
        return $this->insert($data);
    }
    
    /**
     * Cria uma nova consulta de CPF com dados raw e processados separados
     */
    public function createConsultationWithRaw($clientId, $rawApiData, $processedData)
    {
        $data = [
            'client_id' => $clientId,
            'raw_json' => json_encode($rawApiData), // JSON raw da API
            'cpf_valido' => $processedData['cpf_valido'] ?? false,
            'cpf_regular' => $processedData['cpf_regular'] ?? false,
            'dados_divergentes' => $processedData['dados_divergentes'] ?? false,
            'obito' => $processedData['obito'] ?? false,
            'ano_obito' => $processedData['ano_obito'] ?? null,
            'codigo_erro' => $processedData['codigo_erro'] ?? null,
            'mensagem_erro' => $processedData['mensagem_erro'] ?? null,
            'status' => $this->determineStatus($processedData),
            'motivo_reprovacao' => $this->determineRejectionReason($processedData)
        ];
        
        return $this->insert($data);
    }
    
    /**
     * Determina o status baseado nos dados da API
     */
    private function determineStatus($apiData)
    {
        if (!($apiData['cpf_valido'] ?? false)) {
            return 'reprovado';
        }
        
        if (!($apiData['cpf_regular'] ?? false)) {
            return 'reprovado';
        }
        
        if ($apiData['dados_divergentes'] ?? false) {
            return 'reprovado';
        }
        
        if ($apiData['obito'] ?? false) {
            return 'reprovado';
        }
        
        return 'aprovado';
    }
    
    /**
     * Determina o motivo da reprovação
     */
    private function determineRejectionReason($apiData)
    {
        $motivos = [];
        
        if (!($apiData['cpf_valido'] ?? false)) {
            $motivo = 'CPF inválido';
            // Adicionar detalhes do erro se disponível
            if (!empty($apiData['codigo_erro']) || !empty($apiData['mensagem_erro'])) {
                $detalhes = [];
                if (!empty($apiData['codigo_erro'])) {
                    $detalhes[] = "Código: {$apiData['codigo_erro']}";
                }
                if (!empty($apiData['mensagem_erro'])) {
                    $detalhes[] = "Erro: {$apiData['mensagem_erro']}";
                }
                $motivo .= ' (' . implode(', ', $detalhes) . ')';
            }
            $motivos[] = $motivo;
        }
        
        if (!($apiData['cpf_regular'] ?? false)) {
            // Determinar mensagem específica baseada na situação
            $situacao = $apiData['situacao'] ?? 'Desconhecida';
            $motivos[] = "CPF com situação irregular: {$situacao}";
        }
        
        if ($apiData['dados_divergentes'] ?? false) {
            $motivos[] = 'Dados divergentes entre cliente e Receita Federal';
        }
        
        if ($apiData['obito'] ?? false) {
            $motivo = 'Titular falecido';
            // Adicionar ano se disponível
            if (!empty($apiData['ano_obito'])) {
                $motivo .= " (ano: {$apiData['ano_obito']})";
            }
            $motivos[] = $motivo;
        }
        
        return !empty($motivos) ? implode(', ', $motivos) : null;
    }
}