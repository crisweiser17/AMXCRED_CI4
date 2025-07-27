<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-8">
    <!-- Page Header -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Visualizar Cliente</h1>
                <p class="text-gray-600 mt-1">Dados completos do cliente no sistema</p>
            </div>
            <div class="flex space-x-3">
                <a href="<?= base_url('/clients/edit/' . $client['id']) ?>" 
                   class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Editar
                </a>
                <a href="<?= base_url('/clients') ?>" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Voltar
                </a>
            </div>
        </div>
    </div>

    <!-- Dados Pessoais -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
            Dados Pessoais
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Nome Completo -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Nome Completo</label>
                <div class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900">
                    <?= esc($client['full_name']) ?>
                </div>
            </div>

            <!-- CPF -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">CPF</label>
                <div class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900">
                    <?= esc($client['cpf']) ?>
                </div>
            </div>

            <!-- Email -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <div class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900">
                    <?= esc($client['email'] ?: 'Não informado') ?>
                </div>
            </div>

            <!-- Telefone -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Telefone</label>
                <div class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900">
                    <?= esc($client['phone'] ?: 'Não informado') ?>
                </div>
            </div>

            <!-- Data de Nascimento -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Data de Nascimento</label>
                <div class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900">
                    <?= $client['birth_date'] ? date('d/m/Y', strtotime($client['birth_date'])) : 'Não informado' ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Dados PIX -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <svg class="w-5 h-5 text-purple-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            Dados PIX
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Tipo de Chave PIX -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Chave PIX</label>
                <div class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900">
                    <?php
                    $pixTypes = [
                        'cpf' => 'CPF',
                        'email' => 'Email',
                        'phone' => 'Telefone',
                        'random' => 'Chave Aleatória'
                    ];
                    echo esc($pixTypes[$client['pix_key_type']] ?? 'Não informado');
                    ?>
                </div>
            </div>

            <!-- Chave PIX -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Chave PIX</label>
                <div class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900">
                    <?= esc($client['pix_key'] ?: 'Não informado') ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Dados Profissionais -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"></path>
            </svg>
            Dados Profissionais
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Ocupação -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ocupação</label>
                <div class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900">
                    <?php
                    $occupations = [
                        'employee' => 'Funcionário CLT',
                        'self_employed' => 'Autônomo',
                        'entrepreneur' => 'Empresário',
                        'retired' => 'Aposentado',
                        'student' => 'Estudante',
                        'unemployed' => 'Desempregado',
                        'other' => 'Outro'
                    ];
                    echo esc($occupations[$client['occupation']] ?? 'Não informado');
                    ?>
                </div>
            </div>

            <!-- Indústria/Setor -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Indústria/Setor</label>
                <div class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900">
                    <?php
                    $industries = [
                        'technology' => 'Tecnologia',
                        'finance' => 'Financeiro',
                        'healthcare' => 'Saúde',
                        'education' => 'Educação',
                        'retail' => 'Varejo',
                        'manufacturing' => 'Indústria',
                        'services' => 'Serviços',
                        'construction' => 'Construção',
                        'agriculture' => 'Agricultura',
                        'other' => 'Outro'
                    ];
                    echo esc($industries[$client['industry']] ?? 'Não informado');
                    ?>
                </div>
            </div>

            <!-- Tempo de Trabalho -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tempo de Trabalho</label>
                <div class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900">
                    <?php
                    $durations = [
                        1 => 'Menos de 6 meses',
                        2 => '6 meses a 1 ano',
                        3 => '1 a 2 anos',
                        4 => '2 a 5 anos',
                        5 => 'Mais de 5 anos'
                    ];
                    echo esc($durations[$client['employment_duration']] ?? 'Não informado');
                    ?>
                </div>
            </div>

            <!-- Renda Mensal -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Renda Mensal</label>
                <div class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900">
                    <?= $client['monthly_income'] ? 'R$ ' . number_format($client['monthly_income'], 2, ',', '.') : 'Não informado' ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Endereço -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <svg class="w-5 h-5 text-orange-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
            </svg>
            Endereço
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- CEP -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">CEP</label>
                <div class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900">
                    <?= esc($client['zip_code'] ?: 'Não informado') ?>
                </div>
            </div>

            <!-- Rua -->
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1">Rua</label>
                <div class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900">
                    <?= esc($client['street'] ?: 'Não informado') ?>
                </div>
            </div>

            <!-- Número -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Número</label>
                <div class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900">
                    <?= esc($client['number'] ?: 'Não informado') ?>
                </div>
            </div>

            <!-- Complemento -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Complemento</label>
                <div class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900">
                    <?= esc($client['complement'] ?: 'Não informado') ?>
                </div>
            </div>

            <!-- Bairro -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Bairro</label>
                <div class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900">
                    <?= esc($client['neighborhood'] ?: 'Não informado') ?>
                </div>
            </div>

            <!-- Cidade -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Cidade</label>
                <div class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900">
                    <?= esc($client['city'] ?: 'Não informado') ?>
                </div>
            </div>

            <!-- UF -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">UF</label>
                <div class="w-full px-3 py-2 bg-gray-50 border border-gray-200 rounded-lg text-gray-900">
                    <?php
                    $states = [
                        'AC' => 'Acre', 'AL' => 'Alagoas', 'AP' => 'Amapá', 'AM' => 'Amazonas',
                        'BA' => 'Bahia', 'CE' => 'Ceará', 'DF' => 'Distrito Federal', 'ES' => 'Espírito Santo',
                        'GO' => 'Goiás', 'MA' => 'Maranhão', 'MT' => 'Mato Grosso', 'MS' => 'Mato Grosso do Sul',
                        'MG' => 'Minas Gerais', 'PA' => 'Pará', 'PB' => 'Paraíba', 'PR' => 'Paraná',
                        'PE' => 'Pernambuco', 'PI' => 'Piauí', 'RJ' => 'Rio de Janeiro', 'RN' => 'Rio Grande do Norte',
                        'RS' => 'Rio Grande do Sul', 'RO' => 'Rondônia', 'RR' => 'Roraima', 'SC' => 'Santa Catarina',
                        'SP' => 'São Paulo', 'SE' => 'Sergipe', 'TO' => 'Tocantins'
                    ];
                    echo esc($states[$client['state']] ?? $client['state'] ?? 'Não informado');
                    ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Documentos -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
            <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Documentos
        </h2>

        <!-- Comprovantes de Renda -->
        <div class="mb-6">
            <h3 class="text-md font-medium text-gray-900 mb-3">Comprovantes de Renda</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <?php for ($i = 1; $i <= 3; $i++): ?>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            <?= $i ?>º Holerite
                        </label>
                        <?php if (!empty($client["payslip_$i"])): ?>
                            <div class="relative inline-block w-full">
                                <img src="<?= base_url("/documents/thumbnail/{$client['id']}/payslip_$i") ?>"
                                     alt="<?= $i ?>º Holerite"
                                     class="w-full h-32 object-cover rounded-lg border border-gray-200 cursor-pointer hover:opacity-80 transition-opacity"
                                     onclick="openDocumentModal(<?= $client['id'] ?>, 'payslip_<?= $i ?>', '<?= esc($client["payslip_$i"]) ?>')">
                                <div class="absolute inset-0 bg-black bg-opacity-0 hover:bg-opacity-10 rounded-lg transition-all flex items-center justify-center">
                                    <div class="opacity-0 hover:opacity-100 transition-opacity">
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-2">
                                <button type="button"
                                        onclick="openDocumentModal(<?= $client['id'] ?>, 'payslip_<?= $i ?>', '<?= esc($client["payslip_$i"]) ?>')"
                                        class="text-xs bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-full inline-flex items-center">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    Ver Documento
                                </button>
                            </div>
                        <?php else: ?>
                            <div class="w-full h-32 bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center">
                                <div class="text-center">
                                    <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="text-sm text-gray-500">Não enviado</p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endfor; ?>
            </div>
        </div>

        <!-- Documento de Identidade e Selfie -->
        <div class="mb-6">
            <h3 class="text-md font-medium text-gray-900 mb-3">Documento de Identidade e Selfie</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <!-- RG Frente -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">RG Frente</label>
                    <?php if (!empty($client['id_front'])): ?>
                        <div class="relative inline-block w-full">
                            <img src="<?= base_url("/documents/thumbnail/{$client['id']}/id_front") ?>"
                                 alt="RG Frente"
                                 class="w-full h-32 object-cover rounded-lg border border-gray-200 cursor-pointer hover:opacity-80 transition-opacity"
                                 onclick="openDocumentModal(<?= $client['id'] ?>, 'id_front', '<?= esc($client['id_front']) ?>')">
                            <div class="absolute inset-0 bg-black bg-opacity-0 hover:bg-opacity-10 rounded-lg transition-all flex items-center justify-center">
                                <div class="opacity-0 hover:opacity-100 transition-opacity">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="mt-2">
                            <button type="button"
                                    onclick="openDocumentModal(<?= $client['id'] ?>, 'id_front', '<?= esc($client['id_front']) ?>')"
                                    class="text-xs bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-full inline-flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Ver Documento
                            </button>
                        </div>
                    <?php else: ?>
                        <div class="w-full h-32 bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center">
                            <div class="text-center">
                                <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="text-sm text-gray-500">Não enviado</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- RG Verso -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">RG Verso</label>
                    <?php if (!empty($client['id_back'])): ?>
                        <div class="relative inline-block w-full">
                            <img src="<?= base_url("/documents/thumbnail/{$client['id']}/id_back") ?>"
                                 alt="RG Verso"
                                 class="w-full h-32 object-cover rounded-lg border border-gray-200 cursor-pointer hover:opacity-80 transition-opacity"
                                 onclick="openDocumentModal(<?= $client['id'] ?>, 'id_back', '<?= esc($client['id_back']) ?>')">
                            <div class="absolute inset-0 bg-black bg-opacity-0 hover:bg-opacity-10 rounded-lg transition-all flex items-center justify-center">
                                <div class="opacity-0 hover:opacity-100 transition-opacity">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="mt-2">
                            <button type="button"
                                    onclick="openDocumentModal(<?= $client['id'] ?>, 'id_back', '<?= esc($client['id_back']) ?>')"
                                    class="text-xs bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-full inline-flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Ver Documento
                            </button>
                        </div>
                    <?php else: ?>
                        <div class="w-full h-32 bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center">
                            <div class="text-center">
                                <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="text-sm text-gray-500">Não enviado</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Selfie -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Selfie</label>
                    <?php if (!empty($client['selfie'])): ?>
                        <div class="relative inline-block w-full">
                            <img src="<?= base_url("/documents/thumbnail/{$client['id']}/selfie") ?>"
                                 alt="Selfie"
                                 class="w-full h-32 object-cover rounded-lg border border-gray-200 cursor-pointer hover:opacity-80 transition-opacity"
                                 onclick="openDocumentModal(<?= $client['id'] ?>, 'selfie', '<?= esc($client['selfie']) ?>')">
                            <div class="absolute inset-0 bg-black bg-opacity-0 hover:bg-opacity-10 rounded-lg transition-all flex items-center justify-center">
                                <div class="opacity-0 hover:opacity-100 transition-opacity">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <div class="mt-2">
                            <button type="button"
                                    onclick="openDocumentModal(<?= $client['id'] ?>, 'selfie', '<?= esc($client['selfie']) ?>')"
                                    class="text-xs bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-full inline-flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                Ver Documento
                            </button>
                        </div>
                    <?php else: ?>
                        <div class="w-full h-32 bg-gray-100 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center">
                            <div class="text-center">
                                <svg class="w-8 h-8 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <p class="text-sm text-gray-500">Não enviado</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- File Preview JavaScript -->
<script src="<?= base_url('js/file-preview.js') ?>"></script>

<!-- JavaScript para visualização de documentos -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Funções globais para manipulação de documentos
    window.openDocumentModal = function(clientId, documentType, fileName) {
        const modal = document.getElementById('documentModal');
        
        // Buscar informações do documento
        fetch(`<?= base_url('/documents/info/') ?>${clientId}/${documentType}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const modalTitle = document.getElementById('modalTitle');
                    const modalContent = document.getElementById('modalContent');
                    
                    // Definir título
                    const titles = {
                        'payslip_1': '1º Holerite',
                        'payslip_2': '2º Holerite',
                        'payslip_3': '3º Holerite',
                        'id_front': 'RG Frente',
                        'id_back': 'RG Verso',
                        'selfie': 'Selfie'
                    };
                    modalTitle.textContent = titles[documentType] || 'Documento';
                    
                    // Definir conteúdo baseado no tipo
                    if (data.data.isImage) {
                        modalContent.innerHTML = `
                            <img src="/documents/serve/${clientId}/${documentType}"
                                 alt="${modalTitle.textContent}"
                                 class="max-w-full max-h-96 mx-auto rounded-lg shadow-lg">
                            <div class="mt-4 text-sm text-gray-600 text-center">
                                <p>Arquivo: ${data.data.fileName}</p>
                                <p>Tamanho: ${data.data.fileSizeFormatted}</p>
                            </div>
                        `;
                    } else if (data.data.isPDF) {
                        modalContent.innerHTML = `
                            <div class="text-center">
                                <embed src="/documents/serve/${clientId}/${documentType}"
                                       type="application/pdf"
                                       width="100%"
                                       height="500px"
                                       class="rounded-lg shadow-lg">
                                <div class="mt-4 text-sm text-gray-600">
                                    <p>Arquivo: ${data.data.fileName}</p>
                                    <p>Tamanho: ${data.data.fileSizeFormatted}</p>
                                    <a href="<?= base_url('/documents/serve/') ?>${clientId}/${documentType}"
                                       target="_blank"
                                       class="inline-flex items-center px-3 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 mt-2">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        Baixar PDF
                                    </a>
                                </div>
                            </div>
                        `;
                    }
                    
                    // Mostrar modal
                    modal.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';
                } else {
                    alert('Erro ao carregar documento: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Erro ao carregar documento');
            });
    };

    window.closeDocumentModal = function() {
        const modal = document.getElementById('documentModal');
        if (modal) {
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }
    };

    // Fechar modal com ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeDocumentModal();
        }
    });
});
</script>

<!-- Modal para visualização de documentos -->
<div id="documentModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-4xl max-h-full overflow-auto">
        <div class="flex justify-between items-center p-4 border-b">
            <h3 id="modalTitle" class="text-lg font-semibold text-gray-900"></h3>
            <button onclick="closeDocumentModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div id="modalContent" class="p-6">
            <!-- Conteúdo será inserido aqui -->
        </div>
    </div>
</div>

<script>
// Fechar modal ao clicar fora
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('documentModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeDocumentModal();
            }
        });
    }
});
</script>

<?= $this->endSection() ?>