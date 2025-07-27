<?php
// Debug simples para testar planos de empréstimo
require_once 'vendor/autoload.php';

// Configurar ambiente CodeIgniter
$app = \Config\Services::codeigniter();
$app->initialize();

try {
    echo "=== DEBUG PLANOS DE EMPRÉSTIMO ===\n\n";
    
    // Testar conexão com banco
    $db = \Config\Database::connect();
    echo "✓ Conexão com banco estabelecida\n";
    
    // Verificar se tabela existe
    if ($db->tableExists('loan_plans')) {
        echo "✓ Tabela loan_plans existe\n";
    } else {
        echo "✗ Tabela loan_plans NÃO existe\n";
        exit;
    }
    
    // Contar registros
    $count = $db->table('loan_plans')->countAllResults();
    echo "✓ Total de planos: {$count}\n\n";
    
    // Buscar planos
    $plans = $db->table('loan_plans')->get()->getResultArray();
    
    if (empty($plans)) {
        echo "⚠ Nenhum plano encontrado\n";
    } else {
        echo "=== PLANOS ENCONTRADOS ===\n";
        foreach ($plans as $plan) {
            echo "ID: {$plan['id']}\n";
            echo "Nome: {$plan['name']}\n";
            echo "Valor: R$ " . number_format($plan['loan_amount'], 2, ',', '.') . "\n";
            echo "Parcelas: {$plan['number_of_installments']}\n";
            echo "Ativo: " . ($plan['is_active'] ? 'Sim' : 'Não') . "\n";
            echo "---\n";
        }
    }
    
    // Testar modelo
    echo "\n=== TESTE DO MODELO ===\n";
    $loanPlanModel = new \App\Models\LoanPlanModel();
    
    $planWithCalc = $loanPlanModel->findWithCalculations(1);
    if ($planWithCalc) {
        echo "✓ Modelo funcionando\n";
        echo "Plano ID 1:\n";
        echo "- Nome: {$planWithCalc['name']}\n";
        echo "- Parcela: R$ " . number_format($planWithCalc['installment_amount'], 2, ',', '.') . "\n";
        echo "- Taxa mensal: {$planWithCalc['monthly_interest_rate']}%\n";
    } else {
        echo "✗ Erro no modelo ou plano ID 1 não existe\n";
    }
    
    echo "\n=== DEBUG CONCLUÍDO ===\n";
    
} catch (Exception $e) {
    echo "ERRO: " . $e->getMessage() . "\n";
    echo "Arquivo: " . $e->getFile() . "\n";
    echo "Linha: " . $e->getLine() . "\n";
}