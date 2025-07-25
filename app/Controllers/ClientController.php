<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ClientModel;
use App\Models\SettingModel;
use App\Models\CpfConsultationModel;
use App\Models\RiskAnalysisModel;
use App\Helpers\ImageHelper;

class ClientController extends BaseController
{
    protected $clientModel;
    protected $settingModel;
    protected $cpfConsultationModel;
    protected $riskAnalysisModel;

    public function __construct()
    {
        $this->clientModel = new ClientModel();
        $this->settingModel = new SettingModel();
        $this->cpfConsultationModel = new CpfConsultationModel();
        $this->riskAnalysisModel = new RiskAnalysisModel();
    }

    public function index()
    {
        $clients = $this->clientModel->findAll();
        
        // Para cada cliente, verificar elegibilidade
        foreach ($clients as &$client) {
            $client['is_eligible'] = $this->checkClientEligibility($client['id']);
        }
        
        $data = [
            'clients' => $clients,
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
     * Exibe os dados de um cliente (somente leitura)
     */
    public function view($id)
    {
        $client = $this->clientModel->find($id);
        
        if (!$client) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Cliente não encontrado');
        }

        $data = [
            'title' => 'Visualizar Cliente',
            'client' => $client,
            'requiredFields' => $this->settingModel->getRequiredClientFields(),
            'fieldGroups' => $this->settingModel->getClientFieldsGrouped(),
            'occupationOptions' => $this->getOccupationOptions(),
            'industryOptions' => $this->getIndustryOptions(),
            'employmentDurationOptions' => $this->getEmploymentDurationOptions(),
            'pixKeyTypeOptions' => $this->getPixKeyTypeOptions(),
            'stateOptions' => $this->getStateOptions()
        ];

        return view('clients/view', $data);
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
        $uploadedFiles = $this->handleFileUploads();
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
    /**
     * Exibe a página de verificação de cliente
     */
    public function verify($id)
    {
        $client = $this->clientModel->find($id);
        
        if (!$client) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Cliente não encontrado');
        }

        // Buscar dados de verificação existentes
        $cpfConsultation = $this->cpfConsultationModel->getLatestByClientId($id);
        $riskAnalysis = $this->riskAnalysisModel->getLatestByClientId($id);

        // Se há consulta CPF, recalcular dados divergentes dinamicamente
        if ($cpfConsultation && $cpfConsultation['raw_json']) {
            $apiData = json_decode($cpfConsultation['raw_json'], true);
            $processedData = $this->processApiData($apiData);
            $dadosDivergentesAtual = $this->compareClientData($client, $processedData);
            
            // Atualizar no banco se mudou
            if ($dadosDivergentesAtual !== (bool)$cpfConsultation['dados_divergentes']) {
                $this->cpfConsultationModel->update($cpfConsultation['id'], ['dados_divergentes' => $dadosDivergentesAtual]);
                $cpfConsultation['dados_divergentes'] = $dadosDivergentesAtual;
            }
        }

        // Determinar status das verificações
        $visualStatus = $this->getVisualVerificationStatus($client);
        $cpfStatus = $cpfConsultation ? $cpfConsultation['status'] : 'pendente';
        $riskStatus = $riskAnalysis ? $riskAnalysis['status'] : 'pendente';

        $data = [
            'title' => 'Verificação de Cliente',
            'client' => $client,
            'cpfConsultation' => $cpfConsultation,
            'riskAnalysis' => $riskAnalysis,
            'visualStatus' => $visualStatus,
            'cpfStatus' => $cpfStatus,
            'riskStatus' => $riskStatus,
            'isEligible' => $this->checkEligibility($visualStatus, $cpfStatus)
        ];

        return view('clients/verify', $data);
    }

    /**
     * Processa verificação visual (RG + Selfie)
     */
    public function verifyVisual($id)
    {
        $client = $this->clientModel->find($id);
        
        if (!$client) {
            return $this->response->setJSON(['success' => false, 'message' => 'Cliente não encontrado']);
        }

        $action = $this->request->getPost('action'); // 'approve' ou 'reject'
        
        if (!in_array($action, ['approve', 'reject'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Ação inválida']);
        }

        // Atualizar status no campo visual_verification_status (precisaremos adicionar este campo)
        $status = $action === 'approve' ? 'aprovado' : 'reprovado';
        
        // Por enquanto, vamos simular salvando em uma sessão ou cache
        session()->set("visual_verification_{$id}", $status);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Verificação visual ' . ($action === 'approve' ? 'aprovada' : 'reprovada') . ' com sucesso',
            'status' => $status
        ]);
    }

    /**
     * Processa consulta de CPF via API
     */
    public function verifyCpf($id)
    {
        $client = $this->clientModel->find($id);
        
        if (!$client) {
            return $this->response->setJSON(['success' => false, 'message' => 'Cliente não encontrado']);
        }

        try {
            // Fazer consulta na API do CPF - obter JSON raw
            $rawApiData = $this->consultCpfApi($client['cpf']);
            
            // Processar dados para nosso formato
            $processedData = $this->processApiData($rawApiData);
            
            // Comparar dados do cliente com dados da API
            $processedData['dados_divergentes'] = $this->compareClientData($client, $processedData);
            
            // Salvar resultado na tabela cpf_consultation (JSON raw + dados processados)
            $consultationId = $this->cpfConsultationModel->createConsultationWithRaw($id, $rawApiData, $processedData);
            
            if ($consultationId) {
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Consulta de CPF realizada com sucesso',
                    'data' => $processedData
                ]);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Erro ao salvar consulta']);
            }
            
        } catch (\Exception $e) {
            log_message('error', 'Erro na consulta CPF: ' . $e->getMessage());
            return $this->response->setJSON(['success' => false, 'message' => 'Erro na consulta: ' . $e->getMessage()]);
        }
    }

    /**
     * Atualiza dados do cliente com informações da API
     */
    public function updateFromApi($id)
    {
        $cpfConsultation = $this->cpfConsultationModel->getLatestByClientId($id);
        
        if (!$cpfConsultation || !$cpfConsultation['raw_json']) {
            return $this->response->setJSON(['success' => false, 'message' => 'Nenhuma consulta encontrada']);
        }

        $apiData = json_decode($cpfConsultation['raw_json'], true);
        
        // Atualizar apenas nome e data de nascimento
        $updateData = [];
        if (isset($apiData['nome'])) {
            $updateData['full_name'] = $apiData['nome'];
        }
        if (isset($apiData['nascimento'])) {
            // Converter data do formato dd/mm/yyyy para yyyy-mm-dd
            $updateData['birth_date'] = date('Y-m-d', strtotime(str_replace('/', '-', $apiData['nascimento'])));
        }

        if (!empty($updateData)) {
            $updated = $this->clientModel->update($id, $updateData);
            
            if ($updated) {
                // Recalcular dados divergentes após atualização
                $updatedClient = $this->clientModel->find($id);
                $processedData = $this->processApiData($apiData);
                $dadosDivergentes = $this->compareClientData($updatedClient, $processedData);
                
                // Atualizar campo dados_divergentes no banco
                $this->cpfConsultationModel->update($cpfConsultation['id'], ['dados_divergentes' => $dadosDivergentes]);
                
                return $this->response->setJSON(['success' => true, 'message' => 'Dados atualizados com sucesso']);
            }
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Erro ao atualizar dados']);
    }

    /**
     * Processa análise de risco
     */
    public function verifyRisk($id)
    {
        $client = $this->clientModel->find($id);
        
        if (!$client) {
            return $this->response->setJSON(['success' => false, 'message' => 'Cliente não encontrado']);
        }

        $data = $this->request->getPost();
        
        // Validar dados
        $validationRules = [
            'dividas_bancarias' => 'permit_empty|in_list[0,1]',
            'cheque_sem_fundo' => 'permit_empty|in_list[0,1]',
            'protesto_nacional' => 'permit_empty|in_list[0,1]',
            'score' => 'permit_empty|integer|greater_than_equal_to[0]|less_than_equal_to[1000]',
            'recomendacao_serasa' => 'permit_empty|max_length[500]'
        ];

        if (!$this->validate($validationRules)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Dados inválidos', 'errors' => $this->validator->getErrors()]);
        }

        // Converter strings para boolean
        $riskData = [
            'dividas_bancarias' => isset($data['dividas_bancarias']) ? (bool)$data['dividas_bancarias'] : null,
            'cheque_sem_fundo' => isset($data['cheque_sem_fundo']) ? (bool)$data['cheque_sem_fundo'] : null,
            'protesto_nacional' => isset($data['protesto_nacional']) ? (bool)$data['protesto_nacional'] : null,
            'score' => $data['score'] ?? null,
            'recomendacao_serasa' => $data['recomendacao_serasa'] ?? null
        ];

        // Verificar se já existe análise
        $existingAnalysis = $this->riskAnalysisModel->getLatestByClientId($id);
        
        if ($existingAnalysis) {
            $updated = $this->riskAnalysisModel->updateAnalysis($existingAnalysis['id'], $riskData);
            $message = 'Análise de risco atualizada com sucesso';
        } else {
            $updated = $this->riskAnalysisModel->createAnalysis($id, $riskData);
            $message = 'Análise de risco criada com sucesso';
        }

        if ($updated) {
            return $this->response->setJSON(['success' => true, 'message' => $message]);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Erro ao salvar análise']);
        }
    }

    /**
     * Consulta API do CPF - retorna dados raw da API
     */
    private function consultCpfApi($cpf)
    {
        // Carregar configuração da API
        $config = new \Config\CpfApi();
        
        // Remover formatação do CPF
        $cpf = preg_replace('/[^0-9]/', '', $cpf);
        
        // Obter token atual
        $token = $config->getCurrentToken();
        
        if (empty($token)) {
            throw new \Exception("Token da API não configurado");
        }
        
        // Construir URL da API no formato correto: https://api.cpfcnpj.com.br/{token}/{pacote}/{cpf}
        // Pacote 9 = CPF E (consulta completa)
        $url = "https://api.cpfcnpj.com.br/{$token}/9/{$cpf}";
        
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => $config->timeout,
            CURLOPT_HTTPHEADER => [
                'Accept: application/json',
                'Content-Type: application/json'
            ]
        ]);
        
        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $error = curl_error($curl);
        curl_close($curl);
        
        if (!empty($error)) {
            throw new \Exception("Erro na conexão: {$error}");
        }
        
        if ($httpCode !== 200) {
            throw new \Exception("Erro na API: HTTP {$httpCode} - Response: {$response}");
        }
        
        $data = json_decode($response, true);
        
        if (!$data) {
            throw new \Exception("Resposta inválida da API");
        }
        
        // Log da resposta para debug
        log_message('debug', 'Resposta da API CPF: ' . json_encode($data));
        
        // Verificar se houve erro na resposta
        if (isset($data['status']) && $data['status'] === 'error') {
            throw new \Exception("Erro da API: " . ($data['message'] ?? 'Erro desconhecido'));
        }
        
        // Retornar dados raw da API
        return $data;
    }
    
    /**
     * Processa dados raw da API para nosso formato
     */
    private function processApiData($rawData)
    {
        // CPF Válido: baseado no campo 'status' (1 = válido, 0 = inválido)
        $cpfValido = ($rawData['status'] ?? 0) === 1;
        
        // Para status = 0, capturar código de erro se disponível
        $codigoErro = null;
        $mensagemErro = null;
        if (!$cpfValido) {
            $codigoErro = $rawData['errorCodigo'] ?? $rawData['erro'] ?? null;
            $mensagemErro = $rawData['error'] ?? $rawData['message'] ?? null;
        }
        
        // CPF Regular: quando situacao = "Regular" ou "Ativa"
        $cpfRegular = in_array($rawData['situacao'] ?? '', ['Regular', 'Ativa']);
        
        // Óbito: verificar se contém "Titular Falecido" ou "óbito" no JSON
        $obito = false;
        $anoObito = null;
        
        // Verificar em situacaoMotivo
        if (isset($rawData['situacaoMotivo'])) {
            $situacaoMotivo = strtolower($rawData['situacaoMotivo']);
            if (strpos($situacaoMotivo, 'titular falecido') !== false || strpos($situacaoMotivo, 'óbito') !== false) {
                $obito = true;
                // Ano pode estar vazio, mas ainda consideramos óbito
                $anoObito = !empty($rawData['situacaoAnoObito']) ? $rawData['situacaoAnoObito'] : null;
            }
        }
        
        // Verificar em outros campos do JSON se necessário
        if (!$obito) {
            $jsonString = json_encode($rawData);
            if (strpos(strtolower($jsonString), 'titular falecido') !== false || strpos(strtolower($jsonString), 'óbito') !== false) {
                $obito = true;
                $anoObito = !empty($rawData['situacaoAnoObito']) ? $rawData['situacaoAnoObito'] : null;
            }
        }
        
        return [
            'cpf_valido' => $cpfValido,
            'cpf_regular' => $cpfRegular,
            'obito' => $obito,
            'ano_obito' => $anoObito,
            'codigo_erro' => $codigoErro,
            'mensagem_erro' => $mensagemErro,
            'nome' => $rawData['nome'] ?? null,
            'nascimento' => isset($rawData['nascimento']) ? $rawData['nascimento'] : null, // Manter formato original da API
            'situacao' => $rawData['situacao'] ?? null,
            'situacao_motivo' => $rawData['situacaoMotivo'] ?? null,
            'mae' => $rawData['mae'] ?? null,
            'genero' => $rawData['genero'] ?? null
        ];
    }

    /**
     * Compara dados do cliente com dados da API
     */
    private function compareClientData($client, $apiData)
    {
        $divergent = false;
        
        // Comparar nome (normalizar para comparação - remover acentos e espaços extras)
        if (isset($apiData['nome'])) {
            $clientName = $this->normalizeString($client['full_name']);
            $apiName = $this->normalizeString($apiData['nome']);
            
            // Log para debug
            log_message('debug', "Comparando nomes - Cliente: '{$clientName}' vs API: '{$apiName}'");
            
            if ($clientName !== $apiName) {
                $divergent = true;
                log_message('debug', "Nome divergente detectado");
            }
        }
        
        // Comparar data de nascimento
        if (isset($apiData['nascimento'])) {
            $clientBirth = date('d/m/Y', strtotime($client['birth_date']));
            $apiBirth = $apiData['nascimento']; // Manter formato original da API
            
            // Log para debug
            log_message('debug', "Comparando datas - Cliente: '{$clientBirth}' vs API: '{$apiBirth}'");
            
            if ($clientBirth !== $apiBirth) {
                $divergent = true;
                log_message('debug', "Data divergente detectada");
            }
        }
        
        log_message('debug', "Resultado comparação: " . ($divergent ? 'DIVERGENTE' : 'IGUAL'));
        return $divergent;
    }
    
    /**
     * Normaliza string para comparação (remove acentos, espaços extras, converte para minúsculo)
     */
    private function normalizeString($string)
    {
        // Converter para minúsculo
        $string = strtolower(trim($string));
        
        // Remover acentos
        $string = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $string);
        
        // Remover espaços extras
        $string = preg_replace('/\s+/', ' ', $string);
        
        return $string;
    }

    /**
     * Determina status da verificação visual
     */
    private function getVisualVerificationStatus($client)
    {
        // Por enquanto, verificar se tem os documentos necessários
        if (empty($client['id_front']) || empty($client['selfie'])) {
            return 'pendente';
        }
        
        // Verificar se há status salvo na sessão
        $sessionStatus = session()->get("visual_verification_{$client['id']}");
        return $sessionStatus ?? 'pendente';
    }

    /**
     * Verifica elegibilidade para empréstimo
     */
    private function checkEligibility($visualStatus, $cpfStatus)
    {
        return $visualStatus === 'aprovado' && $cpfStatus === 'aprovado';
    }

    /**
     * Verifica elegibilidade de um cliente para empréstimo
     */
    private function checkClientEligibility($clientId)
    {
        // Buscar dados de verificação existentes
        $cpfConsultation = $this->cpfConsultationModel->getLatestByClientId($clientId);
        
        // Determinar status das verificações
        $client = $this->clientModel->find($clientId);
        if (!$client) {
            return false;
        }
        
        $visualStatus = $this->getVisualVerificationStatus($client);
        $cpfStatus = $cpfConsultation ? $cpfConsultation['status'] : 'pendente';
        
        return $this->checkEligibility($visualStatus, $cpfStatus);
    }
}