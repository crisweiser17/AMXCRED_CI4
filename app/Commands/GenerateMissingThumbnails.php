<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Helpers\ImageHelper;

class GenerateMissingThumbnails extends BaseCommand
{
    protected $group       = 'Migration';
    protected $name        = 'generate:missing-thumbnails';
    protected $description = 'Gera thumbnails que faltaram durante a migração';

    public function run(array $params)
    {
        CLI::write('Gerando thumbnails que faltaram...', 'yellow');
        
        $sourcePath = WRITEPATH . 'client_uploads/1/id_back.avif';
        $thumbPath = WRITEPATH . 'client_uploads/1/id_back_thumb.jpg';
        
        if (file_exists($sourcePath)) {
            if (ImageHelper::generateThumbnail($sourcePath, $thumbPath)) {
                CLI::write('✓ Thumbnail gerado com sucesso para id_back.avif', 'green');
            } else {
                CLI::write('✗ Erro ao gerar thumbnail para id_back.avif', 'red');
            }
        } else {
            CLI::write('✗ Arquivo id_back.avif não encontrado', 'red');
        }
        
        CLI::write('Concluído!', 'green');
    }
}