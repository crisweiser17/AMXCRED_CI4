<?php

// Script simples para testar conexão e dados PIX
try {
    // Conectar ao banco usando PDO (MAMP)
    $host = 'localhost';
    $port = '3306';
    $dbname = 'amxcred';
    $username = 'root';
    $password = 'root';
    
    // Tentar conectar usando socket do MAMP
    $dsn = "mysql:unix_socket=/Applications/MAMP/tmp/mysql/mysql.sock;dbname=$dbname;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "Conexão com banco estabelecida com sucesso!\n\n";
    
    // Buscar cliente ID 1
    $stmt = $pdo->prepare('SELECT id, full_name, pix_key_type, pix_key FROM clients WHERE id = 1');
    $stmt->execute();
    $client = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($client) {
        echo "Cliente encontrado:\n";
        echo "ID: " . $client['id'] . "\n";
        echo "Nome: " . $client['full_name'] . "\n";
        echo "PIX Key Type: " . ($client['pix_key_type'] ?: 'NULL') . "\n";
        echo "PIX Key: " . ($client['pix_key'] ?: 'NULL') . "\n";
    } else {
        echo "Cliente não encontrado\n";
        exit;
    }
    
    // Testar atualização direta
    echo "\n--- Testando atualização direta ---\n";
    
    $stmt = $pdo->prepare('UPDATE clients SET pix_key_type = ?, pix_key = ? WHERE id = 1');
    $result = $stmt->execute(['email', 'teste@email.com']);
    
    if ($result) {
        echo "Atualização bem-sucedida\n";
        
        // Verificar se foi salvo
        $stmt = $pdo->prepare('SELECT pix_key_type, pix_key FROM clients WHERE id = 1');
        $stmt->execute();
        $updated = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo "PIX Key Type após update: " . ($updated['pix_key_type'] ?: 'NULL') . "\n";
        echo "PIX Key após update: " . ($updated['pix_key'] ?: 'NULL') . "\n";
    } else {
        echo "Falha na atualização\n";
    }
    
} catch (PDOException $e) {
    echo "Erro de conexão: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage() . "\n";
}