<?php
namespace App\Controllers;

use CodeIgniter\Controller;

class LoanPlansSimple extends Controller
{
    public function index()
    {
        // HTML estático para teste absoluto
        echo '<!DOCTYPE html>
<html>
<head>
    <title>Teste Absoluto - Planos</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        .plan { border: 1px solid #ccc; padding: 10px; margin: 10px 0; }
        .btn { background: #007cba; color: white; padding: 8px 16px; text-decoration: none; border-radius: 4px; }
    </style>
</head>
<body>
    <h1>🔧 TESTE ABSOLUTO - Planos de Empréstimo</h1>
    <p><strong>Status:</strong> HTML estático, sem PHP complexo, sem banco, sem modelo</p>
    
    <div class="plan">
        <h3>Plano Bronze</h3>
        <p>Valor: R$ 1.000,00 | Total: R$ 1.200,00 | 12x de R$ 100,00</p>
        <a href="/loan-simple/view/1" class="btn">Visualizar</a>
    </div>
    
    <div class="plan">
        <h3>Plano Prata</h3>
        <p>Valor: R$ 2.000,00 | Total: R$ 2.400,00 | 12x de R$ 200,00</p>
        <a href="/loan-simple/view/2" class="btn">Visualizar</a>
    </div>
    
    <div class="plan">
        <h3>Plano Ouro</h3>
        <p>Valor: R$ 5.000,00 | Total: R$ 6.000,00 | 12x de R$ 500,00</p>
        <a href="/loan-simple/view/3" class="btn">Visualizar</a>
    </div>
    
    <hr>
    <p><strong>TESTE:</strong> Clique nos botões várias vezes. Se der loop aqui, o problema é no ambiente MAMP/PHP.</p>
    <p><strong>Logs:</strong> Verifique writable/logs/ para mensagens de erro.</p>
</body>
</html>';
        exit;
    }
    
    public function view($id)
    {
        echo '<!DOCTYPE html>
<html>
<head>
    <title>Visualizar Plano ' . $id . '</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        .info { background: #f0f0f0; padding: 15px; margin: 10px 0; }
        .btn { background: #007cba; color: white; padding: 8px 16px; text-decoration: none; border-radius: 4px; margin-right: 10px; }
    </style>
</head>
<body>
    <h1>🔧 VISUALIZAR PLANO ' . $id . '</h1>
    
    <div class="info">
        <h3>Informações do Plano</h3>
        <p><strong>ID:</strong> ' . $id . '</p>
        <p><strong>Nome:</strong> Plano Teste ' . $id . '</p>
        <p><strong>Valor:</strong> R$ 1.000,00</p>
        <p><strong>Total:</strong> R$ 1.200,00</p>
        <p><strong>Parcelas:</strong> 12x de R$ 100,00</p>
    </div>
    
    <div class="info">
        <h3>Teste de Navegação</h3>
        <a href="/loan-simple" class="btn">Voltar para Lista</a>
        <a href="/loan-simple/view/' . $id . '" class="btn">Recarregar Esta Página</a>
    </div>
    
    <div class="info">
        <h3>Debug Info</h3>
        <p><strong>Timestamp:</strong> ' . date('Y-m-d H:i:s') . '</p>
        <p><strong>PHP Version:</strong> ' . PHP_VERSION . '</p>
        <p><strong>Memory Usage:</strong> ' . memory_get_usage(true) . ' bytes</p>
    </div>
    
    <hr>
    <p><strong>TESTE:</strong> Clique em "Voltar" e "Recarregar" várias vezes.</p>
    <p><strong>Se der loop aqui:</strong> O problema é no PHP/MAMP, não no código.</p>
</body>
</html>';
        exit;
    }
}