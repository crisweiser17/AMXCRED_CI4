<?php
namespace App\Controllers;

use App\Controllers\BaseController;

class SettingsControllerDebug extends BaseController
{
    /**
     * Versão ultra-simplificada para debug
     */
    public function viewLoanPlanDebug($id)
    {
        // Log de debug
        log_message('debug', 'Iniciando viewLoanPlanDebug para ID: ' . $id);
        
        try {
            // Validação básica
            if (!is_numeric($id)) {
                log_message('error', 'ID não numérico: ' . $id);
                return redirect()->to('/settings/loan-plans')->with('error', 'ID inválido');
            }
            
            // Conexão direta com banco para evitar problemas no modelo
            $db = \Config\Database::connect();
            
            log_message('debug', 'Conectado ao banco, buscando plano ID: ' . $id);
            
            $query = $db->query("SELECT * FROM loan_plans WHERE id = ? LIMIT 1", [$id]);
            $plan = $query->getRowArray();
            
            if (!$plan) {
                log_message('error', 'Plano não encontrado para ID: ' . $id);
                return redirect()->to('/settings/loan-plans')->with('error', 'Plano não encontrado');
            }
            
            log_message('debug', 'Plano encontrado: ' . json_encode($plan));
            
            // Dados estáticos e seguros
            $planData = [
                'id' => (int)$plan['id'],
                'name' => 'Plano de Teste',
                'loan_amount' => 1000.00,
                'total_repayment_amount' => 1200.00,
                'number_of_installments' => 12,
                'installment_amount' => 100.00,
                'total_interest' => 200.00,
                'monthly_interest_rate' => 1.50,
                'is_active' => true,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            log_message('debug', 'Dados preparados, carregando view');
            
            $data = [
                'title' => 'Debug - Detalhes do Plano',
                'plan' => $planData
            ];

            return view('settings/loan_plan_debug', $data);
            
        } catch (\Exception $e) {
            log_message('error', 'Erro em viewLoanPlanDebug: ' . $e->getMessage());
            return redirect()->to('/settings/loan-plans')->with('error', 'Erro interno: ' . $e->getMessage());
        }
    }
}