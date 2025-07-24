<?php

namespace App\Helpers;

use CodeIgniter\Files\File;

class ImageHelper
{
    /**
     * Dimensões padrão para thumbnails
     */
    const THUMB_WIDTH = 400;
    const THUMB_HEIGHT = 200;
    const THUMB_QUALITY = 85;

    /**
     * Tipos de arquivo suportados para imagens
     */
    const SUPPORTED_IMAGE_TYPES = [
        'image/jpeg',
        'image/jpg',
        'image/png',
        'image/gif',
        'image/webp',
        'image/avif'
    ];

    /**
     * Gera thumbnail com crop inteligente centralizado
     *
     * @param string $sourcePath Caminho da imagem original
     * @param string $thumbPath Caminho onde salvar o thumbnail
     * @param int $width Largura do thumbnail (padrão: 400)
     * @param int $height Altura do thumbnail (padrão: 200)
     * @param int $quality Qualidade JPEG (padrão: 85)
     * @return bool
     */
    public static function generateThumbnail(
        string $sourcePath, 
        string $thumbPath, 
        int $width = self::THUMB_WIDTH, 
        int $height = self::THUMB_HEIGHT,
        int $quality = self::THUMB_QUALITY
    ): bool {
        try {
            // Verificar se o arquivo existe
            if (!file_exists($sourcePath)) {
                log_message('error', "ImageHelper: Arquivo fonte não encontrado: {$sourcePath}");
                return false;
            }

            // Verificar se é uma imagem válida
            $imageInfo = getimagesize($sourcePath);
            if ($imageInfo === false) {
                log_message('error', "ImageHelper: Arquivo não é uma imagem válida: {$sourcePath}");
                return false;
            }

            // Criar diretório de destino se não existir
            $thumbDir = dirname($thumbPath);
            if (!is_dir($thumbDir)) {
                mkdir($thumbDir, 0755, true);
            }

            // Carregar imagem baseada no tipo
            $sourceImage = self::loadImageByType($sourcePath, $imageInfo[2]);
            if ($sourceImage === false) {
                log_message('error', "ImageHelper: Não foi possível carregar a imagem: {$sourcePath}");
                return false;
            }

            // Dimensões originais
            $originalWidth = imagesx($sourceImage);
            $originalHeight = imagesy($sourceImage);

            // Calcular dimensões para crop centralizado
            $cropData = self::calculateCropDimensions($originalWidth, $originalHeight, $width, $height);

            // Criar imagem de destino
            $thumbImage = imagecreatetruecolor($width, $height);
            
            // Preservar transparência para PNG e GIF
            if ($imageInfo[2] == IMAGETYPE_PNG || $imageInfo[2] == IMAGETYPE_GIF) {
                imagealphablending($thumbImage, false);
                imagesavealpha($thumbImage, true);
                $transparent = imagecolorallocatealpha($thumbImage, 255, 255, 255, 127);
                imagefill($thumbImage, 0, 0, $transparent);
            }

            // Fazer o crop e redimensionamento
            imagecopyresampled(
                $thumbImage,
                $sourceImage,
                0, 0,                           // Destino x, y
                $cropData['x'], $cropData['y'], // Origem x, y (crop)
                $width, $height,                // Destino width, height
                $cropData['width'], $cropData['height'] // Origem width, height (crop)
            );

            // Salvar thumbnail
            $success = self::saveImageByType($thumbImage, $thumbPath, $imageInfo[2], $quality);

            // Limpar memória
            imagedestroy($sourceImage);
            imagedestroy($thumbImage);

            if ($success) {
                log_message('info', "ImageHelper: Thumbnail gerado com sucesso: {$thumbPath}");
                return true;
            } else {
                log_message('error', "ImageHelper: Erro ao salvar thumbnail: {$thumbPath}");
                return false;
            }

        } catch (\Exception $e) {
            log_message('error', "ImageHelper: Exceção ao gerar thumbnail: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Calcula dimensões para crop centralizado inteligente
     *
     * @param int $originalWidth
     * @param int $originalHeight
     * @param int $targetWidth
     * @param int $targetHeight
     * @return array
     */
    private static function calculateCropDimensions(
        int $originalWidth, 
        int $originalHeight, 
        int $targetWidth, 
        int $targetHeight
    ): array {
        // Calcular proporções
        $originalRatio = $originalWidth / $originalHeight;
        $targetRatio = $targetWidth / $targetHeight;

        if ($originalRatio > $targetRatio) {
            // Imagem mais larga - crop horizontal
            $cropHeight = $originalHeight;
            $cropWidth = $originalHeight * $targetRatio;
            $cropX = ($originalWidth - $cropWidth) / 2; // Centralizar horizontalmente
            $cropY = 0;
        } else {
            // Imagem mais alta - crop vertical
            $cropWidth = $originalWidth;
            $cropHeight = $originalWidth / $targetRatio;
            $cropX = 0;
            $cropY = ($originalHeight - $cropHeight) / 2; // Centralizar verticalmente
        }

        return [
            'x' => (int) $cropX,
            'y' => (int) $cropY,
            'width' => (int) $cropWidth,
            'height' => (int) $cropHeight
        ];
    }

    /**
     * Carrega imagem baseada no tipo
     *
     * @param string $path
     * @param int $type
     * @return resource|false
     */
    private static function loadImageByType(string $path, int $type)
    {
        switch ($type) {
            case IMAGETYPE_JPEG:
                return imagecreatefromjpeg($path);
            case IMAGETYPE_PNG:
                return imagecreatefrompng($path);
            case IMAGETYPE_GIF:
                return imagecreatefromgif($path);
            case IMAGETYPE_WEBP:
                return imagecreatefromwebp($path);
            case IMAGETYPE_AVIF:
                if (function_exists('imagecreatefromavif')) {
                    return imagecreatefromavif($path);
                }
                return false;
            default:
                return false;
        }
    }

    /**
     * Salva imagem baseada no tipo
     *
     * @param resource $image
     * @param string $path
     * @param int $originalType
     * @param int $quality
     * @return bool
     */
    private static function saveImageByType($image, string $path, int $originalType, int $quality): bool
    {
        // Para thumbnails, sempre salvar como JPEG para consistência e menor tamanho
        return imagejpeg($image, $path, $quality);
    }

    /**
     * Verifica se o arquivo é uma imagem suportada
     *
     * @param string $filePath
     * @return bool
     */
    public static function isValidImage(string $filePath): bool
    {
        if (!file_exists($filePath)) {
            return false;
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $filePath);
        finfo_close($finfo);

        return in_array($mimeType, self::SUPPORTED_IMAGE_TYPES);
    }

    /**
     * Verifica se o arquivo é um PDF
     *
     * @param string $filePath
     * @return bool
     */
    public static function isPDF(string $filePath): bool
    {
        if (!file_exists($filePath)) {
            return false;
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $filePath);
        finfo_close($finfo);

        return $mimeType === 'application/pdf';
    }

    /**
     * Gera thumbnail padrão para PDFs
     *
     * @param string $thumbPath
     * @return bool
     */
    public static function generatePDFThumbnail(string $thumbPath): bool
    {
        try {
            // Criar diretório se não existir
            $thumbDir = dirname($thumbPath);
            if (!is_dir($thumbDir)) {
                mkdir($thumbDir, 0755, true);
            }

            // Criar imagem com ícone PDF
            $image = imagecreatetruecolor(self::THUMB_WIDTH, self::THUMB_HEIGHT);
            
            // Fundo branco
            $white = imagecolorallocate($image, 255, 255, 255);
            $gray = imagecolorallocate($image, 128, 128, 128);
            $red = imagecolorallocate($image, 220, 53, 69);
            
            imagefill($image, 0, 0, $white);
            
            // Desenhar borda
            imagerectangle($image, 0, 0, self::THUMB_WIDTH - 1, self::THUMB_HEIGHT - 1, $gray);
            
            // Desenhar ícone PDF simples
            $centerX = self::THUMB_WIDTH / 2;
            $centerY = self::THUMB_HEIGHT / 2;
            
            // Retângulo do documento
            $docWidth = 80;
            $docHeight = 100;
            $docX = $centerX - $docWidth / 2;
            $docY = $centerY - $docHeight / 2;
            
            imagefilledrectangle($image, $docX, $docY, $docX + $docWidth, $docY + $docHeight, $white);
            imagerectangle($image, $docX, $docY, $docX + $docWidth, $docY + $docHeight, $gray);
            
            // Texto "PDF"
            $fontSize = 5;
            $textWidth = imagefontwidth($fontSize) * 3; // "PDF" = 3 chars
            $textHeight = imagefontheight($fontSize);
            $textX = $centerX - $textWidth / 2;
            $textY = $centerY - $textHeight / 2;
            
            imagestring($image, $fontSize, $textX, $textY, 'PDF', $red);
            
            // Salvar
            $success = imagejpeg($image, $thumbPath, self::THUMB_QUALITY);
            imagedestroy($image);
            
            if ($success) {
                log_message('info', "ImageHelper: Thumbnail PDF gerado: {$thumbPath}");
            }
            
            return $success;
            
        } catch (\Exception $e) {
            log_message('error', "ImageHelper: Erro ao gerar thumbnail PDF: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Remove arquivo se existir
     *
     * @param string $filePath
     * @return bool
     */
    public static function deleteFile(string $filePath): bool
    {
        if (file_exists($filePath)) {
            return unlink($filePath);
        }
        return true;
    }

    /**
     * Obtém extensão do arquivo baseada no tipo MIME
     *
     * @param string $filePath
     * @return string
     */
    public static function getExtensionFromFile(string $filePath): string
    {
        if (!file_exists($filePath)) {
            return '';
        }

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $filePath);
        finfo_close($finfo);

        $extensions = [
            'image/jpeg' => 'jpg',
            'image/jpg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'image/webp' => 'webp',
            'application/pdf' => 'pdf'
        ];

        return $extensions[$mimeType] ?? '';
    }
}