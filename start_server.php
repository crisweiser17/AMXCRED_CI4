<?php

// Script para iniciar o servidor na porta 8081 e atualizar a baseURL automaticamente

$port = 8081;
$host = 'localhost';
$baseURL = "http://{$host}:{$port}/";

// Atualizar o arquivo de configuração App.php
$appConfigPath = __DIR__ . '/app/Config/App.php';
$appConfig = file_get_contents($appConfigPath);

// Substituir a baseURL no arquivo de configuração
$pattern = '/public string \$baseURL = [^;]+;/';
$replacement = "public string \$baseURL = '{$baseURL}';";
$appConfig = preg_replace($pattern, $replacement, $appConfig);

file_put_contents($appConfigPath, $appConfig);

echo "BaseURL atualizada para: {$baseURL}\n";
echo "Iniciando servidor na porta {$port}...\n";

// Limpar cache
exec('php spark cache:clear');

// Iniciar o servidor
passthru("php -S {$host}:{$port} -t public");