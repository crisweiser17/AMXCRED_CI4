<?php
namespace App\Controllers;

use App\Controllers\BaseController;

class LoanPlansController extends BaseController
{
    /**
     * Controller completamente independente para debug
     */
    
    public function index()
    {
        log_message('debug', 'LoanPlansController::index chamado');
        
        try {
            // Conexão direta e simples
            $db = \Config\Database::connect();
            
            // Query mais simples possível
            $query = $db->query("SELECT * FROM loan_plans ORDER BY id ASC");
            $plans = $query->getResultArray();
            
            log_message('debug', 'Planos encontrados: ' . count($plans));
            
            $data = [
                'title' => 'DEBUG - Planos de Empréstimo',
                'plans' => $plans ?? []
            ];

            return view('loan_plans/index_debug', $data);
            
        } catch (\Exception $e) {
            log_message('error', 'Erro em LoanPlansController::index: ' . $e->getMessage());
            
            $data = [
                'title' => 'DEBUG - Planos de Empréstimo',
                'plans' => [],
                'error' => $e->getMessage()
            ];
            
            return view('loan_plans/index_debug', $data);
        }
    }
    
    public function view($id)
    {
        log_message('debug', 'LoanPlansController::view chamado para ID: ' . $id);
        
        try {
            if (!is_numeric($id)) {
                throw new \Exception('ID inválido');
            }
            
            $db = \Config\Database::connect();
            $query = $db->query("SELECT * FROM loan_plans WHERE id = ? LIMIT 1", [$id]);
            $plan = $query->getRowArray();
            
            if (!$plan) {
                throw new \Exception('Plano não encontrado');
            }
            
            log_message('debug', 'Plano encontrado: ' . $plan['name']);
            
            $data = [
                'title' => 'DEBUG - Visualizar Plano',
                'plan' => $plan
            ];

            return view('loan_plans/view_debug', $data);
            
        } catch (\Exception $e) {
            log_message('error', 'Erro em LoanPlansController::view: ' . $e->getMessage());
            return redirect()->to('/loan-plans-debug')->with('error', $e->getMessage());
        }
    }
}