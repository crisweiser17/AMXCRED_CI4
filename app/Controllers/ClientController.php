<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ClientModel;
use App\Models\SettingModel;
use App\Helpers\ImageHelper;

class ClientController extends BaseController
{
    protected $clientModel;
    protected $settingModel;

    public function __construct()
    {
        $this->clientModel = new ClientModel();
        $this->settingModel = new SettingModel();
    }

    public function index()
    {
        $data = [
            'clients' => $this->clientModel->findAll(),
            'title' => 'Lista de Clientes'
        ];

        return view('clients/index', $data);
    }

    /**
     * Exibe o formulário de criação de cliente
     */
    public function create()
    {
        $data = [
            'title' => 'Novo Cliente',
            'requiredFields' => $this->settingModel->getRequiredClientFields(),
            'fieldGroups' => $this->settingModel->getClientFieldsGrouped(),
            'occupationOptions' => $this->getOccupationOptions(),
            'industryOptions' => $this->getIndustryOptions(),
            'employmentDurationOptions' => $this->getEmploymentDurationOptions(),
            'pixKeyTypeOptions' => $this->getPixKeyTypeOptions(),
            'stateOptions' => $this->getStateOptions()
        ];

        return view('clients/create', $data);
    }

    /**
     * Processa o cadastro de um novo cliente
     */
    public function store()
    {
        $requiredFields = $this->settingModel->getRequiredClientFields();
        
        // Construir regras de validação dinamicamente
        $validationRules = $this->buildValidationRules($requiredFields);
        
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
                
                return redirect()->to('/clients')
                               ->with('success', 'Cliente cadastrado com sucesso!');
            } else {
                return redirect()->back()
                               ->withInput()
                               ->with('error', 'Erro ao cadastrar cliente. Tente novamente.');
            }
        } catch (\Exception $e) {
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Erro interno: ' . $e->getMessage());
        }
    }

    /**
     * Exibe o formulário de edição de cliente
     */
    public function edit($id)
    {
        $client = $this->clientModel->find($id);
        
        if (!$client) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Cliente não encontrado');
        }

        // Debug: Log employment_duration value and type
        log_message('debug', 'Employment duration from DB: ' . var_export($client['employment_duration'], true));
        log_message('debug', 'Employment duration type: ' . gettype($client['employment_duration']));
        
        $employmentOptions = $this->getEmploymentDurationOptions();
        log_message('debug', 'Employment duration options: ' . json_encode($employmentOptions));

        $data = [
            'title' => 'Editar Cliente',
            'client' => $client,
            'requiredFields' => $this->settingModel->getRequiredClientFields(),
            'fieldGroups' => $this->settingModel->getClientFieldsGrouped(),
            'occupationOptions' => $this->getOccupationOptions(),
            'industryOptions' => $this->getIndustryOptions(),
            'employmentDurationOptions' => $employmentOptions,
            'pixKeyTypeOptions' => $this->getPixKeyTypeOptions(),
            'stateOptions' => $this->getStateOptions()
        ];

        return view('clients/edit', $data);
    }

    /**
     * Processa a atualização de um cliente
     */
    public function update($id)
    {
        $client = $this->clientModel->find($id);
        
        if (!$client) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Cliente não encontrado');
        }

        $requiredFields = $this->settingModel->getRequiredClientFields();
        
        // Construir regras de validação dinamicamente (excluindo o próprio cliente das validações unique)
        $validationRules = $this->buildValidationRulesForUpdate($requiredFields, $id);
        
        // Adicionar validações específicas para campos PIX (sempre validar se preenchidos)
        $validationRules['pix_key_type'] = 'permit_empty|in_list[cpf,email,phone,random]';
        $validationRules['pix_key'] = 'permit_empty|max_length[150]';
        
        if (!$this->validate($validationRules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }

        $data = $this->request->getPost();
        
        // Debug: Log dos dados recebidos
        log_message('debug', '=== INÍCIO DEBUG PIX UPDATE ===');
        log_message('debug', 'Cliente ID: ' . $id);
        log_message('debug', 'Dados POST recebidos: ' . json_encode($data));
        log_message('debug', 'Dados PIX antes do processamento - Type: ' . ($data['pix_key_type'] ?? 'NULL') . ', Key: ' . ($data['pix_key'] ?? 'NULL'));
        
        // Verificar dados atuais no banco antes da atualização
        $currentClient = $this->clientModel->find($id);
        log_message('debug', 'Dados PIX atuais no banco - Type: ' . ($currentClient['pix_key_type'] ?? 'NULL') . ', Key: ' . ($currentClient['pix_key'] ?? 'NULL'));
        
        // Garantir que os campos PIX sejam sempre incluídos nos dados
        if (isset($data['pix_key_type'])) {
            $data['pix_key_type'] = $data['pix_key_type'];
            log_message('debug', 'PIX Key Type encontrado: ' . $data['pix_key_type']);
        } else {
            log_message('debug', 'PIX Key Type NÃO encontrado nos dados POST');
        }
        if (isset($data['pix_key'])) {
            $data['pix_key'] = $data['pix_key'];
            log_message('debug', 'PIX Key encontrado: ' . $data['pix_key']);
        } else {
            log_message('debug', 'PIX Key NÃO encontrado nos dados POST');
        }
        
        // Processar uploads de arquivos (apenas se novos arquivos foram enviados)
        $uploadedFiles = $this->handleFileUploads($id);
        if (!empty($uploadedFiles)) {
            $data = array_merge($data, $uploadedFiles);
        }

        try {
            // Debug: Log dos dados que serão salvos
            log_message('debug', 'Dados que serão salvos no banco: ' . json_encode($data));
            log_message('debug', 'Dados PIX que serão salvos - Type: ' . ($data['pix_key_type'] ?? 'NULL') . ', Key: ' . ($data['pix_key'] ?? 'NULL'));
            
            $updated = $this->clientModel->update($id, $data);
            
            // Debug: Verificar se a atualização foi bem-sucedida e os dados foram salvos
            log_message('debug', 'Resultado da atualização: ' . ($updated ? 'SUCCESS' : 'FAILED'));
            
            if ($updated) {
                // Verificar os dados após a atualização
                $updatedClient = $this->clientModel->find($id);
                log_message('debug', 'Dados PIX após atualização no banco - Type: ' . ($updatedClient['pix_key_type'] ?? 'NULL') . ', Key: ' . ($updatedClient['pix_key'] ?? 'NULL'));
                log_message('debug', '=== FIM DEBUG PIX UPDATE ===');
                
                return redirect()->to('/clients')
                               ->with('success', 'Cliente atualizado com sucesso!');
            } else {
                log_message('debug', 'ERRO: Atualização retornou FALSE');
                log_message('debug', '=== FIM DEBUG PIX UPDATE ===');
                return redirect()->back()
                               ->withInput()
                               ->with('error', 'Erro ao atualizar cliente. Tente novamente.');
            }
        } catch (\Exception $e) {
            log_message('debug', 'EXCEÇÃO durante atualização: ' . $e->getMessage());
            log_message('debug', '=== FIM DEBUG PIX UPDATE ===');
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Erro interno: ' . $e->getMessage());
        }
    }

    /**
     * Constrói regras de validação para atualização (excluindo o próprio registro das validações unique)
     */
    private function buildValidationRulesForUpdate($requiredFields, $clientId)
    {
        $rules = [
            'full_name' => 'required|min_length[3]|max_length[150]',
            'cpf' => "required|min_length[11]|max_length[14]|is_unique[clients.cpf,id,{$clientId}]",
        ];

        // Adicionar regras para campos obrigatórios configurados
        foreach ($requiredFields as $field) {
            switch ($field) {
                case 'email':
                    $rules['email'] = "required|valid_email|is_unique[clients.email,id,{$clientId}]";
                    break;
                case 'phone':
                    $rules['phone'] = 'required|min_length[10]|max_length[20]';
                    break;
                case 'birth_date':
                    $rules['birth_date'] = 'required|valid_date';
                    break;
                case 'occupation':
                    $rules['occupation'] = 'required|max_length[100]';
                    break;
                case 'industry':
                    $rules['industry'] = 'required|max_length[100]';
                    break;
                case 'employment_duration':
                    $rules['employment_duration'] = 'required|integer';
                    break;
                case 'monthly_income':
                    $rules['monthly_income'] = 'required|decimal';
                    break;
                case 'pix_key_type':
                    $rules['pix_key_type'] = 'required|in_list[cpf,email,phone,random]';
                    break;
                case 'pix_key':
                    $rules['pix_key'] = 'required|max_length[150]';
                    break;
                case 'zip_code':
                    $rules['zip_code'] = 'required|max_length[10]';
                    break;
                case 'street':
                    $rules['street'] = 'required|max_length[150]';
                    break;
                case 'number':
                    $rules['number'] = 'required|max_length[10]';
                    break;
                case 'neighborhood':
                    $rules['neighborhood'] = 'required|max_length[100]';
                    break;
                case 'city':
                    $rules['city'] = 'required|max_length[100]';
                    break;
                case 'state':
                    $rules['state'] = 'required|exact_length[2]';
                    break;
            }
        }

        return $rules;
    }

    /**
     * Constrói regras de validação baseadas nos campos obrigatórios
     */
    private function buildValidationRules($requiredFields)
    {
        $rules = [
            'full_name' => 'required|min_length[3]|max_length[150]',
            'cpf' => 'required|min_length[11]|max_length[14]|is_unique[clients.cpf]',
        ];

        // Adicionar regras para campos obrigatórios configurados
        foreach ($requiredFields as $field) {
            switch ($field) {
                case 'email':
                    $rules['email'] = 'required|valid_email|is_unique[clients.email]';
                    break;
                case 'phone':
                    $rules['phone'] = 'required|min_length[10]|max_length[20]';
                    break;
                case 'birth_date':
                    $rules['birth_date'] = 'required|valid_date';
                    break;
                case 'occupation':
                    $rules['occupation'] = 'required|max_length[100]';
                    break;
                case 'industry':
                    $rules['industry'] = 'required|max_length[100]';
                    break;
                case 'employment_duration':
                    $rules['employment_duration'] = 'required|integer';
                    break;
                case 'monthly_income':
                    $rules['monthly_income'] = 'required|decimal';
                    break;
                case 'pix_key_type':
                    $rules['pix_key_type'] = 'required|in_list[cpf,email,phone,random]';
                    break;
                case 'pix_key':
                    $rules['pix_key'] = 'required|max_length[150]';
                    break;
                case 'zip_code':
                    $rules['zip_code'] = 'required|max_length[10]';
                    break;
                case 'street':
                    $rules['street'] = 'required|max_length[150]';
                    break;
                case 'number':
                    $rules['number'] = 'required|max_length[10]';
                    break;
                case 'neighborhood':
                    $rules['neighborhood'] = 'required|max_length[100]';
                    break;
                case 'city':
                    $rules['city'] = 'required|max_length[100]';
                    break;
                case 'state':
                    $rules['state'] = 'required|exact_length[2]';
                    break;
                // Arquivos obrigatórios serão validados separadamente
            }
        }

        return $rules;
    }

    /**
     * Processa uploads de arquivos com nova estrutura organizada por cliente
     */
    private function handleFileUploads($clientId = null)
    {
        $uploadedFiles = [];
        $fileFields = ['payslip_1', 'payslip_2', 'payslip_3', 'id_front', 'id_back', 'selfie'];
        
        foreach ($fileFields as $field) {
            $file = $this->request->getFile($field);
            
            if ($file && $file->isValid() && !$file->hasMoved()) {
                // Se não temos clientId, usar estrutura antiga temporariamente
                if ($clientId === null) {
                    $newName = $file->getRandomName();
                    $file->move(WRITEPATH . 'uploads', $newName);
                    $uploadedFiles[$field] = $newName;
                } else {
                    // Nova estrutura organizada por cliente
                    $result = $this->handleClientFileUpload($file, $clientId, $field);
                    if ($result) {
                        $uploadedFiles[$field] = $result;
                    }
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

            log_message('info', "Arquivo uploaded: {$filePath}");
            return $fileName;

        } catch (\Exception $e) {
            log_message('error', "Erro no upload do arquivo {$documentType}: " . $e->getMessage());
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
            log_message('error', "Erro ao gerar thumbnail para {$documentType}: " . $e->getMessage());
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
            '3' => 'Menos de 6 meses',        // Mediana: 0-6 meses = 3 meses
            '9' => '6 meses a 1 ano',         // Mediana: 6-12 meses = 9 meses
            '18' => '1 a 2 anos',             // Mediana: 12-24 meses = 18 meses
            '42' => '2 a 5 anos',             // Mediana: 24-60 meses = 42 meses
            '90' => '5 a 10 anos',            // Mediana: 60-120 meses = 90 meses
            '150' => 'Mais de 10 anos'        // Estimativa: 10+ anos = ~12.5 anos = 150 meses
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