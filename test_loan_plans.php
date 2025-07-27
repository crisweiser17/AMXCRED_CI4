<?php

// Teste simples para verificar se os planos de empréstimo estão funcionando
require_once 'vendor/autoload.php';

// Configurar o ambiente do CodeIgniter
$app = \Config\Services::codeigniter();
$app->initialize();

try {
    // Testar conexão com o banco
    $db = \Config\Database::connect();
    echo "✅ Conexão com banco OK\n";
    
    // Testar se a tabela existe
    if ($db->tableExists('loan_plans')) {
        echo "✅ Tabela loan_plans existe\n";
        
        // Testar se há dados
        $query = $db->query("SELECT COUNT(*) as total FROM loan_plans");
        $result = $query->getRow();
        echo "✅ Total de planos: " . $result->total . "\n";
        
        // Testar se consegue buscar um plano
        $query = $db->query("SELECT * FROM loan_plans LIMIT 1");
        $plan = $query->getRow();
        
        if ($plan) {
            echo "✅ Exemplo de plano encontrado:\n";
            echo "   ID: " . $plan->id . "\n";
            echo "   Nome: " . $plan->name . "\n";
            echo "   Valor: R$ " . number_format($plan->loan_amount, 2, ',', '.') . "\n";
            echo "   Parcelas: " . $plan->number_of_installments . "x\n";
        } else {
            echo "⚠️  Nenhum plano encontrado na tabela\n";
        }
        
    } else {
        echo "❌ Tabela loan_plans não existe\n";
    }
    
} catch (Exception $e) {
    echo "❌ Erro: " . $e->getMessage() . "\n";
}

echo "\n=== Teste concluído ===\n";