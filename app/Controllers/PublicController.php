<?php

namespace App\Controllers;

use App\Models\ClientModel;
use App\Models\SettingModel;
use App\Helpers\ImageHelper;
use CodeIgniter\Controller;

class PublicController extends Controller
{
    protected $clientModel;
    protected $settingModel;

    public function __construct()
    {
        $this->clientModel = new ClientModel();
        $this->settingModel = new SettingModel();
    }

    /**
     * Exibe o formulário público de cadastro
     */
    public function register()
    {
        $data = [
            'title' => 'Cadastro de Cliente - AMX Cred',
            'requiredFields' => $this->getPublicRequiredFields(),
            'occupationOptions' => $this->getOccupationOptions(),
            'industryOptions' => $this->getIndustryOptions(),
            'employmentDurationOptions' => $this->getEmploymentDurationOptions(),
            'pixKeyTypeOptions' => $this->getPixKeyTypeOptions(),
            'stateOptions' => $this->getStateOptions()
        ];

        return view('public/register', $data);
    }

    /**
     * Processa o cadastro público de cliente
     */
    public function store()
    {
        $requiredFields = $this->getPublicRequiredFields();
        
        // Construir regras de validação
        $validationRules = $this->buildPublicValidationRules($requiredFields);
        
        if (!$this->validate($validationRules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }

        $data = $this->request->getPost();

        try {
            // Primeiro, inserir cliente sem arquivos para obter o ID
            $clientId = $this->clientModel->insert($data);
            
            if ($clientId) {
                // Agora processar uploads com o ID do cliente
                $uploadedFiles = $this->handleFileUploads($clientId);
                
                // Atualizar cliente com os nomes dos arquivos se houver uploads
                if (!empty($uploadedFiles)) {
                    $this->clientModel->update($clientId, $uploadedFiles);
                }
                
                return redirect()->to('/register/success')
                               ->with('success', 'Cadastro realizado com sucesso!');
            } else {
                return redirect()->back()
                               ->withInput()
                               ->with('error', 'Erro ao cadastrar cliente. Tente novamente.');
            }
        } catch (\Exception $e) {
            log_message('error', 'Erro no cadastro público: ' . $e->getMessage());
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Erro interno: ' . $e->getMessage());
        }
    }

    /**
     * Exibe página de sucesso do cadastro
     */
    public function success()
    {
        $data = [
            'title' => 'Cadastro Realizado - AMX Cred'
        ];

        return view('public/success', $data);
    }

    /**
     * Define campos obrigatórios para cadastro público
     */
    private function getPublicRequiredFields()
    {
        return [
            'full_name', 'cpf', 'email', 'phone', 'birth_date',
            'occupation', 'industry', 'employment_duration', 'monthly_income',
            'pix_key_type', 'pix_key',
            'zip_code', 'street', 'number', 'neighborhood', 'city', 'state',
            'payslip_1', 'payslip_2', 'payslip_3', 'id_front', 'id_back', 'selfie'
        ];
    }

    /**
     * Constrói regras de validação para cadastro público
     */
    private function buildPublicValidationRules($requiredFields)
    {
        $rules = [
            'full_name' => 'required|min_length[3]|max_length[150]',
            'cpf' => 'required|min_length[11]|max_length[14]|is_unique[clients.cpf]',
            'email' => 'required|valid_email|is_unique[clients.email]',
            'phone' => 'required|min_length[10]|max_length[20]',
            'birth_date' => 'required|valid_date',
            'occupation' => 'required|max_length[100]',
            'industry' => 'required|max_length[100]',
            'employment_duration' => 'required|integer',
            'monthly_income' => 'required|decimal',
            'pix_key_type' => 'required|in_list[cpf,email,phone,random]',
            'pix_key' => 'required|max_length[150]',
            'zip_code' => 'required|max_length[10]',
            'street' => 'required|max_length[150]',
            'number' => 'required|max_length[10]',
            'neighborhood' => 'required|max_length[100]',
            'city' => 'required|max_length[100]',
            'state' => 'required|exact_length[2]',
            'complement' => 'permit_empty|max_length[100]'
        ];

        // Validação de arquivos obrigatórios
        $fileFields = ['payslip_1', 'payslip_2', 'payslip_3', 'id_front', 'id_back', 'selfie'];
        foreach ($fileFields as $field) {
            if (in_array($field, $requiredFields)) {
                if ($field === 'selfie') {
                    $rules[$field] = 'uploaded[' . $field . ']|max_size[' . $field . ',5120]|ext_in[' . $field . ',jpg,jpeg,png]';
                } else {
                    $rules[$field] = 'uploaded[' . $field . ']|max_size[' . $field . ',5120]|ext_in[' . $field . ',jpg,jpeg,png,pdf]';
                }
            }
        }

        return $rules;
    }

    /**
     * Processa uploads de arquivos para cadastro público
     */
    private function handleFileUploads($clientId)
    {
        $uploadedFiles = [];
        $fileFields = ['payslip_1', 'payslip_2', 'payslip_3', 'id_front', 'id_back', 'selfie'];
        
        foreach ($fileFields as $field) {
            $file = $this->request->getFile($field);
            
            if ($file && $file->isValid() && !$file->hasMoved()) {
                $result = $this->handleClientFileUpload($file, $clientId, $field);
                if ($result) {
                    $uploadedFiles[$field] = $result;
                }
            }
        }
        
        return $uploadedFiles;
    }

    /**
     * Processa upload de arquivo individual para cliente específico
     */
    private function handleClientFileUpload($file, $clientId, $documentType)
    {
        try {
            // Criar diretório do cliente se não existir
            $clientDir = WRITEPATH . 'client_uploads/' . $clientId;
            if (!is_dir($clientDir)) {
                mkdir($clientDir, 0755, true);
            }

            // Determinar extensão baseada no tipo MIME
            $extension = ImageHelper::getExtensionFromFile($file->getTempName());
            if (empty($extension)) {
                // Fallback para extensão original
                $extension = $file->getClientExtension();
            }

            // Nome do arquivo: tipo_documento.extensao
            $fileName = $documentType . '.' . $extension;
            $filePath = $clientDir . '/' . $fileName;

            // Remover arquivo anterior se existir
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            // Mover arquivo
            $file->move($clientDir, $fileName);

            // Gerar thumbnail automaticamente
            $this->generateDocumentThumbnail($filePath, $clientId, $documentType);

            log_message('info', "Arquivo público uploaded: {$filePath}");
            return $fileName;

        } catch (\Exception $e) {
            log_message('error', "Erro no upload público do arquivo {$documentType}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Gera thumbnail para documento
     */
    private function generateDocumentThumbnail($filePath, $clientId, $documentType)
    {
        try {
            $thumbnailPath = WRITEPATH . 'client_uploads/' . $clientId . '/' . $documentType . '_thumb.jpg';

            // Remover thumbnail anterior se existir
            if (file_exists($thumbnailPath)) {
                unlink($thumbnailPath);
            }

            // Gerar novo thumbnail
            if (ImageHelper::isValidImage($filePath)) {
                ImageHelper::generateThumbnail($filePath, $thumbnailPath);
            } elseif (ImageHelper::isPDF($filePath)) {
                ImageHelper::generatePDFThumbnail($thumbnailPath);
            }

        } catch (\Exception $e) {
            log_message('error', "Erro ao gerar thumbnail público para {$documentType}: " . $e->getMessage());
        }
    }

    /**
     * Opções para ocupação
     */
    private function getOccupationOptions()
    {
        return [
            'assalariado' => 'Assalariado',
            'empresario' => 'Empresário',
            'autonomo' => 'Autônomo',
            'profissional_liberal' => 'Profissional Liberal',
            'aposentado' => 'Aposentado',
            'estudante' => 'Estudante',
            'desempregado' => 'Desempregado',
            'servidor_publico' => 'Servidor Público',
            'produtor_rural' => 'Produtor Rural',
            'outros' => 'Outros'
        ];
    }

    /**
     * Opções para indústria/setor
     */
    private function getIndustryOptions()
    {
        return [
            'industria_transformacao' => 'Indústria/Transformação',
            'comercio_varejo' => 'Comércio (Varejo)',
            'comercio_atacado' => 'Comércio (Atacado)',
            'servicos_saude' => 'Serviços - Saúde',
            'servicos_educacao' => 'Serviços - Educação',
            'servicos_financas' => 'Serviços - Finanças',
            'servicos_outros' => 'Serviços - Outros',
            'agropecuaria' => 'Agropecuária',
            'construcao_civil' => 'Construção Civil',
            'tecnologia_informacao' => 'Tecnologia/Informação',
            'transporte_logistica' => 'Transporte/Logística',
            'setor_publico' => 'Setor Público',
            'outros' => 'Outros'
        ];
    }

    /**
     * Opções para tempo de trabalho
     */
    private function getEmploymentDurationOptions()
    {
        return [
            '3' => 'Menos de 6 meses',
            '9' => '6 meses a 1 ano',
            '18' => '1 a 2 anos',
            '42' => '2 a 5 anos',
            '90' => '5 a 10 anos',
            '150' => 'Mais de 10 anos'
        ];
    }

    /**
     * Opções para tipo de chave PIX
     */
    private function getPixKeyTypeOptions()
    {
        return [
            'cpf' => 'CPF',
            'email' => 'Email',
            'phone' => 'Telefone',
            'random' => 'Chave Aleatória'
        ];
    }

    /**
     * Opções para estados brasileiros
     */
    private function getStateOptions()
    {
        return [
            'AC' => 'Acre',
            'AL' => 'Alagoas',
            'AP' => 'Amapá',
            'AM' => 'Amazonas',
            'BA' => 'Bahia',
            'CE' => 'Ceará',
            'DF' => 'Distrito Federal',
            'ES' => 'Espírito Santo',
            'GO' => 'Goiás',
            'MA' => 'Maranhão',
            'MT' => 'Mato Grosso',
            'MS' => 'Mato Grosso do Sul',
            'MG' => 'Minas Gerais',
            'PA' => 'Pará',
            'PB' => 'Paraíba',
            'PR' => 'Paraná',
            'PE' => 'Pernambuco',
            'PI' => 'Piauí',
            'RJ' => 'Rio de Janeiro',
            'RN' => 'Rio Grande do Norte',
            'RS' => 'Rio Grande do Sul',
            'RO' => 'Rondônia',
            'RR' => 'Roraima',
            'SC' => 'Santa Catarina',
            'SP' => 'São Paulo',
            'SE' => 'Sergipe',
            'TO' => 'Tocantins'
        ];
    }
}