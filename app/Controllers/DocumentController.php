<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ClientModel;
use App\Helpers\ImageHelper;

class DocumentController extends BaseController
{
    protected $clientModel;

    public function __construct()
    {
        $this->clientModel = new ClientModel();
    }

    /**
     * Serve documento original do cliente
     *
     * @param int $clientId
     * @param string $documentType
     * @return mixed
     */
    public function serve($clientId, $documentType)
    {
        try {
            // Validar cliente
            $client = $this->clientModel->find($clientId);
            if (!$client) {
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Cliente não encontrado');
            }

            // Validar tipo de documento
            $validDocuments = ['payslip_1', 'payslip_2', 'payslip_3', 'id_front', 'id_back', 'selfie'];
            if (!in_array($documentType, $validDocuments)) {
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Tipo de documento inválido');
            }

            // Verificar se o cliente tem esse documento
            $fileName = $client[$documentType];
            if (empty($fileName)) {
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Documento não encontrado');
            }

            // Construir caminho do arquivo
            $filePath = $this->getClientDocumentPath($clientId, $documentType, $fileName);
            
            if (!file_exists($filePath)) {
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Arquivo não encontrado');
            }

            // Determinar tipo MIME
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $filePath);
            finfo_close($finfo);

            // Servir arquivo
            return $this->response
                ->setHeader('Content-Type', $mimeType)
                ->setHeader('Content-Length', filesize($filePath))
                ->setHeader('Cache-Control', 'public, max-age=3600')
                ->setBody(file_get_contents($filePath));

        } catch (\Exception $e) {
            log_message('error', 'DocumentController::serve - ' . $e->getMessage());
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Erro ao carregar documento');
        }
    }

    /**
     * Serve thumbnail do documento
     *
     * @param int $clientId
     * @param string $documentType
     * @return mixed
     */
    public function thumbnail($clientId, $documentType)
    {
        try {
            // Validar cliente
            $client = $this->clientModel->find($clientId);
            if (!$client) {
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Cliente não encontrado');
            }

            // Validar tipo de documento
            $validDocuments = ['payslip_1', 'payslip_2', 'payslip_3', 'id_front', 'id_back', 'selfie'];
            if (!in_array($documentType, $validDocuments)) {
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Tipo de documento inválido');
            }

            // Verificar se o cliente tem esse documento
            $fileName = $client[$documentType];
            if (empty($fileName)) {
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Documento não encontrado');
            }

            // Construir caminhos
            $originalPath = $this->getClientDocumentPath($clientId, $documentType, $fileName);
            $thumbnailPath = $this->getClientThumbnailPath($clientId, $documentType);

            // Verificar se o arquivo original existe
            if (!file_exists($originalPath)) {
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Arquivo original não encontrado');
            }

            // Gerar thumbnail se não existir
            if (!file_exists($thumbnailPath)) {
                $this->generateThumbnail($originalPath, $thumbnailPath);
            }

            // Verificar se thumbnail foi gerado com sucesso
            if (!file_exists($thumbnailPath)) {
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Erro ao gerar thumbnail');
            }

            // Servir thumbnail
            return $this->response
                ->setHeader('Content-Type', 'image/jpeg')
                ->setHeader('Content-Length', filesize($thumbnailPath))
                ->setHeader('Cache-Control', 'public, max-age=86400') // Cache por 24h
                ->setBody(file_get_contents($thumbnailPath));

        } catch (\Exception $e) {
            log_message('error', 'DocumentController::thumbnail - ' . $e->getMessage());
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Erro ao carregar thumbnail');
        }
    }

    /**
     * Remove documento do cliente
     *
     * @param int $clientId
     * @param string $documentType
     * @return mixed
     */
    public function delete($clientId, $documentType)
    {
        try {
            // Validar cliente
            $client = $this->clientModel->find($clientId);
            if (!$client) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Cliente não encontrado'
                ])->setStatusCode(404);
            }

            // Validar tipo de documento
            $validDocuments = ['payslip_1', 'payslip_2', 'payslip_3', 'id_front', 'id_back', 'selfie'];
            if (!in_array($documentType, $validDocuments)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Tipo de documento inválido'
                ])->setStatusCode(400);
            }

            // Verificar se o cliente tem esse documento
            $fileName = $client[$documentType];
            if (empty($fileName)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Documento não encontrado'
                ])->setStatusCode(404);
            }

            // Construir caminhos
            $originalPath = $this->getClientDocumentPath($clientId, $documentType, $fileName);
            $thumbnailPath = $this->getClientThumbnailPath($clientId, $documentType);

            // Remover arquivos
            ImageHelper::deleteFile($originalPath);
            ImageHelper::deleteFile($thumbnailPath);

            // Atualizar banco de dados
            $updateData = [$documentType => null];
            $this->clientModel->update($clientId, $updateData);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Documento removido com sucesso'
            ]);

        } catch (\Exception $e) {
            log_message('error', 'DocumentController::delete - ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erro interno do servidor'
            ])->setStatusCode(500);
        }
    }

    /**
     * Obtém caminho do documento do cliente
     *
     * @param int $clientId
     * @param string $documentType
     * @param string $fileName
     * @return string
     */
    private function getClientDocumentPath($clientId, $documentType, $fileName): string
    {
        // Para arquivos existentes (nome aleatório), usar estrutura antiga
        if (strpos($fileName, '_') !== false && strlen($fileName) > 20) {
            return WRITEPATH . 'uploads/' . $fileName;
        }
        
        // Para nova estrutura
        return WRITEPATH . 'client_uploads/' . $clientId . '/' . $fileName;
    }

    /**
     * Obtém caminho do thumbnail do cliente
     *
     * @param int $clientId
     * @param string $documentType
     * @return string
     */
    private function getClientThumbnailPath($clientId, $documentType): string
    {
        return WRITEPATH . 'client_uploads/' . $clientId . '/' . $documentType . '_thumb.jpg';
    }

    /**
     * Gera thumbnail para o documento
     *
     * @param string $originalPath
     * @param string $thumbnailPath
     * @return bool
     */
    private function generateThumbnail($originalPath, $thumbnailPath): bool
    {
        // Verificar se é imagem ou PDF
        if (ImageHelper::isValidImage($originalPath)) {
            return ImageHelper::generateThumbnail($originalPath, $thumbnailPath);
        } elseif (ImageHelper::isPDF($originalPath)) {
            return ImageHelper::generatePDFThumbnail($thumbnailPath);
        }
        
        return false;
    }

    /**
     * Obtém informações do documento
     *
     * @param int $clientId
     * @param string $documentType
     * @return mixed
     */
    public function info($clientId, $documentType)
    {
        try {
            // Validar cliente
            $client = $this->clientModel->find($clientId);
            if (!$client) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Cliente não encontrado'
                ])->setStatusCode(404);
            }

            // Validar tipo de documento
            $validDocuments = ['payslip_1', 'payslip_2', 'payslip_3', 'id_front', 'id_back', 'selfie'];
            if (!in_array($documentType, $validDocuments)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Tipo de documento inválido'
                ])->setStatusCode(400);
            }

            // Verificar se o cliente tem esse documento
            $fileName = $client[$documentType];
            if (empty($fileName)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Documento não encontrado'
                ])->setStatusCode(404);
            }

            // Construir caminho do arquivo
            $filePath = $this->getClientDocumentPath($clientId, $documentType, $fileName);
            
            if (!file_exists($filePath)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Arquivo não encontrado'
                ])->setStatusCode(404);
            }

            // Obter informações do arquivo
            $fileSize = filesize($filePath);
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $filePath);
            finfo_close($finfo);

            $isImage = ImageHelper::isValidImage($filePath);
            $isPDF = ImageHelper::isPDF($filePath);

            return $this->response->setJSON([
                'success' => true,
                'data' => [
                    'fileName' => $fileName,
                    'fileSize' => $fileSize,
                    'fileSizeFormatted' => $this->formatFileSize($fileSize),
                    'mimeType' => $mimeType,
                    'isImage' => $isImage,
                    'isPDF' => $isPDF,
                    'documentType' => $documentType,
                    'clientId' => $clientId
                ]
            ]);

        } catch (\Exception $e) {
            log_message('error', 'DocumentController::info - ' . $e->getMessage());
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Erro interno do servidor'
            ])->setStatusCode(500);
        }
    }

    /**
     * Formata tamanho do arquivo
     *
     * @param int $bytes
     * @return string
     */
    private function formatFileSize($bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= pow(1024, $pow);
        
        return round($bytes, 2) . ' ' . $units[$pow];
    }
}