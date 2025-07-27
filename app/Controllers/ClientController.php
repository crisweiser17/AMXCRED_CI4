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
        try {
            // Parâmetros de busca e filtros
            $search = $this->request->getGet('search') ?? '';
            $eligibility = $this->request->getGet('eligibility') ?? 'all';
            $dateFrom = $this->request->getGet('date_from') ?? '';
            $dateTo = $this->request->getGet('date_to') ?? '';
            $orderBy = $this->request->getGet('order_by') ?? 'full_name';
            $orderDir = $this->request->getGet('order_dir') ?? 'asc';
            $page = (int)($this->request->getGet('page') ?? 1);
            $perPage = 20;
            
            // Se for requisição AJAX, retornar apenas os dados
            if ($this->request->isAJAX()) {
                $result = $this->clientModel->getClientsWithFilters([
                    'search' => $search,
                    'eligibility' => $eligibility,
                    'date_from' => $dateFrom,
                    'date_to' => $dateTo,
                    'order_by' => $orderBy,
                    'order_dir' => $orderDir,
                    'page' => $page,
                    'per_page' => $perPage
                ]);
                
                return $this->response->setJSON($result);
            }
            
            // Buscar clientes com filtros
            $result = $this->clientModel->getClientsWithFilters([
                'search' => $search,
                'eligibility' => $eligibility,
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
                'order_by' => $orderBy,
                'order_dir' => $orderDir,
                'page' => $page,
                'per_page' => $perPage
            ]);
            
            $data = [
                'clients' => $result['data'],
                'pagination' => $result['pagination'],
                'filters' => [
                    'search' => $search,
                    'eligibility' => $eligibility,
                    'date_from' => $dateFrom,
                    'date_to' => $dateTo,
                    'order_by' => $orderBy,
                    'order_dir' => $orderDir
                ],
                'title' => 'Lista de Clientes'
            ];

            return view('clients/index', $data);
        } catch (\Exception $e) {
            log_message('error', 'Exception in ClientController::index(): ' . $e->getMessage());
            throw $e;
        }
    }

    public function create()
    {
        try {
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
        } catch (\Exception $e) {
            log_message('error', 'Exception in ClientController::create(): ' . $e->getMessage());
            throw $e;
        }
    }

    public function store()
    {
        $requiredFields = $this->settingModel->getRequiredClientFields();
        $validationRules = $this->buildValidationRules($requiredFields);
        
        if (!$this->validate($validationRules)) {
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }

        $data = $this->request->getPost();

        try {
            $clientId = $this->clientModel->insert($data);
            
            if ($clientId) {
                $uploadedFiles = $this->handleFileUploads($clientId);
                
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

    public function view($id)
    {
        try {
            log_message('info', '[DEBUG] ClientController::view() - INÍCIO para cliente ID: ' . $id);
            
            $client = $this->clientModel->find($id);
            log_message('info', '[DEBUG] ClientController::view() - Cliente carregado do banco');
            
            if (!$client) {
                log_message('error', '[DEBUG] ClientController::view() - Cliente não encontrado: ' . $id);
                throw new \CodeIgniter\Exceptions\PageNotFoundException('Cliente não encontrado');
            }

            log_message('info', '[DEBUG] ClientController::view() - Carregando configurações...');
            $requiredFields = $this->settingModel->getRequiredClientFields();
            log_message('info', '[DEBUG] ClientController::view() - Required fields carregados');
            
            $fieldGroups = $this->settingModel->getClientFieldsGrouped();
            log_message('info', '[DEBUG] ClientController::view() - Field groups carregados');

            $data = [
                'title' => 'Visualizar Cliente',
                'client' => $client,
                'requiredFields' => $requiredFields,
                'fieldGroups' => $fieldGroups,
                'occupationOptions' => $this->getOccupationOptions(),
                'industryOptions' => $this->getIndustryOptions(),
                'employmentDurationOptions' => $this->getEmploymentDurationOptions(),
                'pixKeyTypeOptions' => $this->getPixKeyTypeOptions(),
                'stateOptions' => $this->getStateOptions()
            ];

            log_message('info', '[DEBUG] ClientController::view() - Dados preparados, retornando view');
            return view('clients/view', $data);
        } catch (\Exception $e) {
            log_message('error', '[DEBUG] Exception in ClientController::view(): ' . $e->getMessage());
            log_message('error', '[DEBUG] Stack trace: ' . $e->getTraceAsString());
            throw $e;
        }
    }

    public function edit($id)
    {
        $client = $this->clientModel->find($id);
        
        if (!$client) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Cliente não encontrado');
        }

        $data = [
            'title' => 'Editar Cliente',
            'client' => $client,
            'requiredFields' => $this->settingModel->getRequiredClientFields(),
            'fieldGroups' => $this->settingModel->getClientFieldsGrouped(),
            'occupationOptions' => $this->getOccupationOptions(),
            'industryOptions' => $this->getIndustryOptions(),
            'employmentDurationOptions' => $this->getEmploymentDurationOptions(),
            'pixKeyTypeOptions' => $this->getPixKeyTypeOptions(),
            'stateOptions' => $this->getStateOptions()
        ];

        return view('clients/edit', $data);
    }

    public function update($id)
    {
        log_message('info', '[DEBUG] ClientController::update() - INÍCIO da execução para cliente ID: ' . $id);
        
        $client = $this->clientModel->find($id);
        
        if (!$client) {
            log_message('error', '[DEBUG] ClientController::update() - Cliente não encontrado: ' . $id);
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Cliente não encontrado');
        }

        log_message('info', '[DEBUG] ClientController::update() - Cliente encontrado: ' . $client['full_name']);

        $requiredFields = $this->settingModel->getRequiredClientFields();
        $validationRules = $this->buildValidationRulesForUpdate($requiredFields, $id);
        
        $validationRules['pix_key_type'] = 'permit_empty|in_list[cpf,email,phone,random]';
        $validationRules['pix_key'] = 'permit_empty|max_length[150]';
        
        if (!$this->validate($validationRules)) {
            log_message('info', '[DEBUG] ClientController::update() - Validação falhou, redirecionando de volta');
            return redirect()->back()
                           ->withInput()
                           ->with('errors', $this->validator->getErrors());
        }

        log_message('info', '[DEBUG] ClientController::update() - Validação passou, processando dados');
        $data = $this->request->getPost();
        
        $uploadedFiles = $this->handleFileUploads($id);
        if (!empty($uploadedFiles)) {
            $data = array_merge($data, $uploadedFiles);
            log_message('info', '[DEBUG] ClientController::update() - Arquivos processados: ' . count($uploadedFiles));
        }

        try {
            log_message('info', '[DEBUG] ClientController::update() - Tentando atualizar cliente no banco');
            $updated = $this->clientModel->update($id, $data);
            
            if ($updated) {
                log_message('info', '[DEBUG] ClientController::update() - Cliente atualizado com sucesso, redirecionando para /clients');
                return redirect()->to('/clients')
                               ->with('success', 'Cliente atualizado com sucesso!');
            } else {
                log_message('error', '[DEBUG] ClientController::update() - Falha ao atualizar cliente no banco');
                return redirect()->back()
                               ->withInput()
                               ->with('error', 'Erro ao atualizar cliente. Tente novamente.');
            }
        } catch (\Exception $e) {
            log_message('error', '[DEBUG] ClientController::update() - Exception: ' . $e->getMessage());
            log_message('error', '[DEBUG] ClientController::update() - Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()
                           ->withInput()
                           ->with('error', 'Erro interno: ' . $e->getMessage());
        }
    }

    public function verify($id)
    {
        $client = $this->clientModel->find($id);
        
        if (!$client) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Cliente não encontrado');
        }

        $cpfConsultation = $this->cpfConsultationModel->getLatestByClientId($id);
        $riskAnalysis = $this->riskAnalysisModel->getLatestByClientId($id);

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

    public function verifyVisual($id)
    {
        $client = $this->clientModel->find($id);
        
        if (!$client) {
            return $this->response->setJSON(['success' => false, 'message' => 'Cliente não encontrado']);
        }

        $action = $this->request->getPost('action');
        
        if (!in_array($action, ['approve', 'reject'])) {
            return $this->response->setJSON(['success' => false, 'message' => 'Ação inválida']);
        }

        $status = $action === 'approve' ? 'aprovado' : 'reprovado';
        session()->set("visual_verification_{$id}", $status);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Verificação visual ' . ($action === 'approve' ? 'aprovada' : 'reprovada') . ' com sucesso',
            'status' => $status
        ]);
    }

    public function verifyCpf($id)
    {
        $client = $this->clientModel->find($id);
        
        if (!$client) {
            return $this->response->setJSON(['success' => false, 'message' => 'Cliente não encontrado']);
        }

        try {
            $rawApiData = $this->consultCpfApi($client['cpf']);
            $processedData = $this->processApiData($rawApiData);
            $processedData['dados_divergentes'] = $this->compareClientData($client, $processedData);
            
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

    public function updateFromApi($id)
    {
        $cpfConsultation = $this->cpfConsultationModel->getLatestByClientId($id);
        
        if (!$cpfConsultation || !$cpfConsultation['raw_json']) {
            return $this->response->setJSON(['success' => false, 'message' => 'Nenhuma consulta encontrada']);
        }

        $apiData = json_decode($cpfConsultation['raw_json'], true);
        
        $updateData = [];
        if (isset($apiData['nome'])) {
            $updateData['full_name'] = $apiData['nome'];
        }
        if (isset($apiData['nascimento'])) {
            $updateData['birth_date'] = date('Y-m-d', strtotime(str_replace('/', '-', $apiData['nascimento'])));
        }

        if (!empty($updateData)) {
            $updated = $this->clientModel->update($id, $updateData);
            
            if ($updated) {
                return $this->response->setJSON(['success' => true, 'message' => 'Dados atualizados com sucesso']);
            }
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Erro ao atualizar dados']);
    }

    public function verifyRisk($id)
    {
        $client = $this->clientModel->find($id);
        
        if (!$client) {
            return $this->response->setJSON(['success' => false, 'message' => 'Cliente não encontrado']);
        }

        $data = $this->request->getPost();
        
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

        $riskData = [
            'dividas_bancarias' => isset($data['dividas_bancarias']) ? (bool)$data['dividas_bancarias'] : null,
            'cheque_sem_fundo' => isset($data['cheque_sem_fundo']) ? (bool)$data['cheque_sem_fundo'] : null,
            'protesto_nacional' => isset($data['protesto_nacional']) ? (bool)$data['protesto_nacional'] : null,
            'score' => $data['score'] ?? null,
            'recomendacao_serasa' => $data['recomendacao_serasa'] ?? null
        ];

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

    private function buildValidationRulesForUpdate($requiredFields, $clientId)
    {
        $rules = [
            'full_name' => 'required|min_length[3]|max_length[150]',
            'cpf' => "required|min_length[11]|max_length[14]|is_unique[clients.cpf,id,{$clientId}]",
        ];

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

    private function buildValidationRules($requiredFields)
    {
        $rules = [
            'full_name' => 'required|min_length[3]|max_length[150]',
            'cpf' => 'required|min_length[11]|max_length[14]|is_unique[clients.cpf]',
        ];

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
            }
        }

        return $rules;
    }

    private function handleFileUploads($clientId = null)
    {
        $uploadedFiles = [];
        $fileFields = ['payslip_1', 'payslip_2', 'payslip_3', 'id_front', 'id_back', 'selfie'];
        
        foreach ($fileFields as $field) {
            $file = $this->request->getFile($field);
            
            if ($file && $file->isValid() && !$file->hasMoved()) {
                if ($clientId === null) {
                    $newName = $file->getRandomName();
                    $file->move(WRITEPATH . 'uploads', $newName);
                    $uploadedFiles[$field] = $newName;
                } else {
                    $result = $this->handleClientFileUpload($file, $clientId, $field);
                    if ($result) {
                        $uploadedFiles[$field] = $result;
                    }
                }
            }
        }
        
        return $uploadedFiles;
    }

    private function handleClientFileUpload($file, $clientId, $documentType)
    {
        try {
            $clientDir = WRITEPATH . 'client_uploads/' . $clientId;
            if (!is_dir($clientDir)) {
                mkdir($clientDir, 0755, true);
            }

            $extension = ImageHelper::getExtensionFromFile($file->getTempName());
            if (empty($extension)) {
                $extension = $file->getClientExtension();
            }

            $fileName = $documentType . '.' . $extension;
            $filePath = $clientDir . '/' . $fileName;

            if (file_exists($filePath)) {
                unlink($filePath);
            }

            $file->move($clientDir, $fileName);
            $this->generateDocumentThumbnail($filePath, $clientId, $documentType);

            return $fileName;

        } catch (\Exception $e) {
            log_message('error', "Erro no upload do arquivo {$documentType}: " . $e->getMessage());
            return false;
        }
    }

    private function generateDocumentThumbnail($filePath, $clientId, $documentType)
    {
        try {
            $thumbnailPath = WRITEPATH . 'client_uploads/' . $clientId . '/' . $documentType . '_thumb.jpg';

            if (file_exists($thumbnailPath)) {
                unlink($thumbnailPath);
            }

            if (ImageHelper::isValidImage($filePath)) {
                ImageHelper::generateThumbnail($filePath, $thumbnailPath);
            } elseif (ImageHelper::isPDF($filePath)) {
                ImageHelper::generatePDFThumbnail($thumbnailPath);
            }

        } catch (\Exception $e) {
            log_message('error', "Erro ao gerar thumbnail para {$documentType}: " . $e->getMessage());
        }
    }

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

    private function getPixKeyTypeOptions()
    {
        return [
            'cpf' => 'CPF',
            'email' => 'Email',
            'phone' => 'Telefone',
            'random' => 'Chave Aleatória'
        ];
    }

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

    private function consultCpfApi($cpf)
    {
        $config = new \Config\CpfApi();
        $cpf = preg_replace('/[^0-9]/', '', $cpf);
        $token = $config->getCurrentToken();
        
        if (empty($token)) {
            throw new \Exception("Token da API não configurado");
        }
        
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
        
        if (isset($data['status']) && $data['status'] === 'error') {
            throw new \Exception("Erro da API: " . ($data['message'] ?? 'Erro desconhecido'));
        }
        
        return $data;
    }
    
    private function processApiData($rawData)
    {
        $cpfValido = ($rawData['status'] ?? 0) === 1;
        
        $codigoErro = null;
        $mensagemErro = null;
        if (!$cpfValido) {
            $codigoErro = $rawData['errorCodigo'] ?? $rawData['erro'] ?? null;
            $mensagemErro = $rawData['error'] ?? $rawData['message'] ?? null;
        }
        
        $cpfRegular = in_array($rawData['situacao'] ?? '', ['Regular', 'Ativa']);
        
        $obito = false;
        $anoObito = null;
        
        if (isset($rawData['situacaoMotivo'])) {
            $situacaoMotivo = strtolower($rawData['situacaoMotivo']);
            if (strpos($situacaoMotivo, 'titular falecido') !== false || strpos($situacaoMotivo, 'óbito') !== false) {
                $obito = true;
                $anoObito = !empty($rawData['situacaoAnoObito']) ? $rawData['situacaoAnoObito'] : null;
            }
        }
        
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
            'nascimento' => isset($rawData['nascimento']) ? $rawData['nascimento'] : null,
            'situacao' => $rawData['situacao'] ?? null,
            'situacao_motivo' => $rawData['situacaoMotivo'] ?? null,
            'mae' => $rawData['mae'] ?? null,
            'genero' => $rawData['genero'] ?? null
        ];
    }

    private function compareClientData($client, $apiData)
    {
        $divergent = false;
        
        if (isset($apiData['nome'])) {
            $clientName = $this->normalizeString($client['full_name']);
            $apiName = $this->normalizeString($apiData['nome']);
            
            if ($clientName !== $apiName) {
                $divergent = true;
            }
        }
        
        if (isset($apiData['nascimento'])) {
            $clientBirth = date('d/m/Y', strtotime($client['birth_date']));
            $apiBirth = $apiData['nascimento'];
            
            if ($clientBirth !== $apiBirth) {
                $divergent = true;
            }
        }
        
        return $divergent;
    }
    
    private function normalizeString($string)
    {
        $string = strtolower(trim($string));
        $string = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $string);
        $string = preg_replace('/\s+/', ' ', $string);
        
        return $string;
    }

    private function getVisualVerificationStatus($client)
    {
        if (empty($client['id_front']) || empty($client['selfie'])) {
            return 'pendente';
        }
        
        $sessionStatus = session()->get("visual_verification_{$client['id']}");
        return $sessionStatus ?? 'pendente';
    }

    private function checkEligibility($visualStatus, $cpfStatus)
    {
        return $visualStatus === 'aprovado' && $cpfStatus === 'aprovado';
    }

    /**
     * Verifica elegibilidade de forma simples e segura (sem consultas complexas)
     */
    private function checkClientEligibilitySimple($clientId)
    {
        // Versão simplificada que sempre retorna false por padrão
        // A verificação completa deve ser feita na página individual do cliente
        // Isso evita loops infinitos e consultas pesadas na listagem
        return false;
    }
}
