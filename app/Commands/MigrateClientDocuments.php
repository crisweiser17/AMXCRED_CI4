<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\ClientModel;
use App\Helpers\ImageHelper;

class MigrateClientDocuments extends BaseCommand
{
    protected $group       = 'Migration';
    protected $name        = 'migrate:client-documents';
    protected $description = 'Migra documentos de clientes da estrutura antiga para a nova estrutura organizada por cliente';

    public function run(array $params)
    {
        CLI::write('Iniciando migração de documentos de clientes...', 'yellow');
        
        $clientModel = new ClientModel();
        $clients = $clientModel->findAll();
        
        if (empty($clients)) {
            CLI::write('Nenhum cliente encontrado.', 'red');
            return;
        }
        
        $totalClients = count($clients);
        $migratedCount = 0;
        $errorCount = 0;
        
        CLI::write("Encontrados {$totalClients} clientes para migração.", 'green');
        
        foreach ($clients as $client) {
            CLI::write("Processando cliente ID: {$client['id']} - {$client['full_name']}", 'cyan');
            
            try {
                $migrated = $this->migrateClientDocuments($client);
                if ($migrated) {
                    $migratedCount++;
                    CLI::write("✓ Cliente {$client['id']} migrado com sucesso", 'green');
                } else {
                    CLI::write("- Cliente {$client['id']} não tinha documentos para migrar", 'yellow');
                }
            } catch (\Exception $e) {
                $errorCount++;
                CLI::write("✗ Erro ao migrar cliente {$client['id']}: " . $e->getMessage(), 'red');
            }
        }
        
        CLI::write('', 'white');
        CLI::write('=== RESUMO DA MIGRAÇÃO ===', 'yellow');
        CLI::write("Total de clientes: {$totalClients}", 'white');
        CLI::write("Clientes migrados: {$migratedCount}", 'green');
        CLI::write("Erros: {$errorCount}", $errorCount > 0 ? 'red' : 'green');
        CLI::write('Migração concluída!', 'green');
    }
    
    private function migrateClientDocuments($client): bool
    {
        $clientModel = new ClientModel();
        $documentFields = ['payslip_1', 'payslip_2', 'payslip_3', 'id_front', 'id_back', 'selfie'];
        $hasMigration = false;
        $updateData = [];
        
        // Criar diretório do cliente
        $clientDir = WRITEPATH . 'client_uploads/' . $client['id'];
        if (!is_dir($clientDir)) {
            mkdir($clientDir, 0755, true);
        }
        
        foreach ($documentFields as $field) {
            $fileName = $client[$field];
            
            if (empty($fileName)) {
                continue;
            }
            
            // Caminho do arquivo antigo
            $oldPath = WRITEPATH . 'uploads/' . $fileName;
            
            if (!file_exists($oldPath)) {
                CLI::write("  - Arquivo não encontrado: {$fileName}", 'yellow');
                continue;
            }
            
            try {
                // Determinar extensão do arquivo
                $extension = ImageHelper::getExtensionFromFile($oldPath);
                if (empty($extension)) {
                    // Fallback para extensão do nome do arquivo
                    $pathInfo = pathinfo($fileName);
                    $extension = $pathInfo['extension'] ?? 'jpg';
                }
                
                // Novo nome do arquivo: campo.extensao
                $newFileName = $field . '.' . $extension;
                $newPath = $clientDir . '/' . $newFileName;
                
                // Copiar arquivo para nova localização
                if (copy($oldPath, $newPath)) {
                    CLI::write("  ✓ {$field}: {$fileName} → {$newFileName}", 'green');
                    
                    // Gerar thumbnail
                    $this->generateThumbnail($newPath, $client['id'], $field);
                    
                    // Marcar para atualização no banco
                    $updateData[$field] = $newFileName;
                    $hasMigration = true;
                    
                    // Remover arquivo antigo (opcional - comentado por segurança)
                    // unlink($oldPath);
                    
                } else {
                    CLI::write("  ✗ Erro ao copiar {$field}: {$fileName}", 'red');
                }
                
            } catch (\Exception $e) {
                CLI::write("  ✗ Erro ao processar {$field}: " . $e->getMessage(), 'red');
            }
        }
        
        // Atualizar banco de dados se houve migração
        if ($hasMigration && !empty($updateData)) {
            $clientModel->update($client['id'], $updateData);
            CLI::write("  ✓ Banco de dados atualizado", 'green');
        }
        
        return $hasMigration;
    }
    
    private function generateThumbnail($filePath, $clientId, $documentType): void
    {
        try {
            $thumbnailPath = WRITEPATH . 'client_uploads/' . $clientId . '/' . $documentType . '_thumb.jpg';
            
            if (ImageHelper::isValidImage($filePath)) {
                ImageHelper::generateThumbnail($filePath, $thumbnailPath);
                CLI::write("    ✓ Thumbnail gerado para {$documentType}", 'cyan');
            } elseif (ImageHelper::isPDF($filePath)) {
                ImageHelper::generatePDFThumbnail($thumbnailPath);
                CLI::write("    ✓ Thumbnail PDF gerado para {$documentType}", 'cyan');
            }
        } catch (\Exception $e) {
            CLI::write("    ✗ Erro ao gerar thumbnail para {$documentType}: " . $e->getMessage(), 'red');
        }
    }
}