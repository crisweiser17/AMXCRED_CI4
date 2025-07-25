<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto">
    <!-- Page Header -->
    <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Verificação de Cliente</h1>
                <div class="mt-2">
                    <p class="text-lg font-semibold text-gray-800">
                        Cliente: <span class="text-blue-700"><?= esc($client['full_name']) ?></span>
                    </p>
                    <p class="text-lg font-semibold text-gray-800">
                        CPF: <span class="text-blue-700"><?= esc($client['cpf']) ?></span>
                    </p>
                    
                    <!-- Badge de Elegibilidade -->
                    <div class="mt-3">
                        <?php if ($isEligible): ?>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 border border-green-200">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Elegível para Empréstimos
                            </span>
                        <?php else: ?>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Verificações Pendentes
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="<?= base_url('/clients') ?>" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Voltar para Clientes
                </a>
                <a href="<?= base_url('/clients/edit/' . $client['id']) ?>" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Editar Cliente
                </a>
            </div>
        </div>
    </div>

    <!-- Status Badges -->
    <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Verificação Visual -->
            <div class="flex items-center p-4 rounded-lg border <?= $visualStatus === 'aprovado' ? 'bg-green-50 border-green-200' : ($visualStatus === 'reprovado' ? 'bg-red-50 border-red-200' : 'bg-yellow-50 border-yellow-200') ?>">
                <div class="flex-shrink-0">
                    <?php if ($visualStatus === 'aprovado'): ?>
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    <?php elseif ($visualStatus === 'reprovado'): ?>
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    <?php else: ?>
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    <?php endif; ?>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium <?= $visualStatus === 'aprovado' ? 'text-green-800' : ($visualStatus === 'reprovado' ? 'text-red-800' : 'text-yellow-800') ?>">
                        Verificação Visual
                    </p>
                    <p class="text-xs <?= $visualStatus === 'aprovado' ? 'text-green-600' : ($visualStatus === 'reprovado' ? 'text-red-600' : 'text-yellow-600') ?>">
                        <?= ucfirst($visualStatus) ?>
                    </p>
                </div>
            </div>

            <!-- Consulta CPF -->
            <div class="flex items-center p-4 rounded-lg border <?= $cpfStatus === 'aprovado' ? 'bg-green-50 border-green-200' : ($cpfStatus === 'reprovado' ? 'bg-red-50 border-red-200' : 'bg-yellow-50 border-yellow-200') ?>">
                <div class="flex-shrink-0">
                    <?php if ($cpfStatus === 'aprovado'): ?>
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    <?php elseif ($cpfStatus === 'reprovado'): ?>
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    <?php else: ?>
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    <?php endif; ?>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium <?= $cpfStatus === 'aprovado' ? 'text-green-800' : ($cpfStatus === 'reprovado' ? 'text-red-800' : 'text-yellow-800') ?>">
                        Consulta CPF
                    </p>
                    <p class="text-xs <?= $cpfStatus === 'aprovado' ? 'text-green-600' : ($cpfStatus === 'reprovado' ? 'text-red-600' : 'text-yellow-600') ?>">
                        <?= ucfirst($cpfStatus) ?>
                        <?php if ($cpfStatus === 'reprovado' && $cpfConsultation && $cpfConsultation['motivo_reprovacao']): ?>
                            - <?= esc($cpfConsultation['motivo_reprovacao']) ?>
                        <?php endif; ?>
                    </p>
                </div>
            </div>

            <!-- Análise de Risco -->
            <div class="flex items-center p-4 rounded-lg border <?= $riskStatus === 'consultado' ? 'bg-green-50 border-green-200' : 'bg-yellow-50 border-yellow-200' ?>">
                <div class="flex-shrink-0">
                    <?php if ($riskStatus === 'consultado'): ?>
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    <?php else: ?>
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    <?php endif; ?>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium <?= $riskStatus === 'consultado' ? 'text-green-800' : 'text-yellow-800' ?>">
                        Análise de Risco
                    </p>
                    <p class="text-xs <?= $riskStatus === 'consultado' ? 'text-green-600' : 'text-yellow-600' ?>">
                        <?= ucfirst($riskStatus) ?> (Opcional)
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Dados do Cliente -->
    <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Dados do Cliente</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Nome</label>
                <p class="mt-1 text-sm text-gray-900"><?= esc($client['full_name']) ?></p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">CPF</label>
                <p class="mt-1 text-sm text-gray-900"><?= esc($client['cpf']) ?></p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Data de Nascimento</label>
                <p class="mt-1 text-sm text-gray-900"><?= date('d/m/Y', strtotime($client['birth_date'])) ?></p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Email</label>
                <p class="mt-1 text-sm text-gray-900"><?= esc($client['email']) ?></p>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Telefone</label>
                <p class="mt-1 text-sm text-gray-900"><?= esc($client['phone']) ?></p>
            </div>
        </div>
    </div>

    <!-- Verificação Visual -->
    <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-medium text-gray-900">1. Verificação de Documentos (Obrigatória)</h2>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $visualStatus === 'aprovado' ? 'bg-green-100 text-green-800' : ($visualStatus === 'reprovado' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') ?>">
                <?= ucfirst($visualStatus) ?>
            </span>
        </div>

        <?php if (!empty($client['id_front']) && !empty($client['selfie'])): ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- RG Frente -->
                <div>
                    <h3 class="text-sm font-medium text-gray-700 mb-2">RG - Frente</h3>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4">
                        <img src="<?= base_url('/documents/thumbnail/' . $client['id'] . '/id_front') ?>" 
                             alt="RG Frente" 
                             class="max-w-full h-auto rounded cursor-pointer"
                             onclick="openImageModal('<?= base_url('/documents/serve/' . $client['id'] . '/id_front') ?>', 'RG - Frente')">
                    </div>
                </div>

                <!-- Selfie -->
                <div>
                    <h3 class="text-sm font-medium text-gray-700 mb-2">Selfie</h3>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4">
                        <img src="<?= base_url('/documents/thumbnail/' . $client['id'] . '/selfie') ?>" 
                             alt="Selfie" 
                             class="max-w-full h-auto rounded cursor-pointer"
                             onclick="openImageModal('<?= base_url('/documents/serve/' . $client['id'] . '/selfie') ?>', 'Selfie')">
                    </div>
                </div>
            </div>

            <!-- Botões de Verificação Visual -->
            <div class="flex space-x-3">
                <button type="button" 
                        onclick="verifyVisual('approve')"
                        class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Aprovar
                </button>
                <button type="button" 
                        onclick="verifyVisual('reject')"
                        class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:bg-red-700 active:bg-red-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    Reprovar
                </button>
            </div>
        <?php else: ?>
            <div class="text-center py-8">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Documentos não encontrados</h3>
                <p class="mt-1 text-sm text-gray-500">O cliente precisa fazer upload do RG (frente) e selfie para verificação.</p>
            </div>
        <?php endif; ?>
    </div>

    <!-- Consulta CPF -->
    <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-medium text-gray-900">2. Verificação de CPF (Obrigatória)</h2>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $cpfStatus === 'aprovado' ? 'bg-green-100 text-green-800' : ($cpfStatus === 'reprovado' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') ?>">
                <?= ucfirst($cpfStatus) ?>
            </span>
        </div>

        <?php if ($cpfConsultation): ?>
            <!-- Exibir dados da consulta -->
            <div class="mb-4">
                <p class="text-sm text-gray-600 mb-2">Última consulta: <?= date('d/m/Y H:i', strtotime($cpfConsultation['created_at'])) ?></p>
                
                <!-- Comparação de dados se houver divergência -->
                <?php if ($cpfConsultation['dados_divergentes']): ?>
                    <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4 mb-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-yellow-800">Dados divergentes encontrados</h3>
                                <p class="mt-1 text-sm text-yellow-700">Os dados do cliente não coincidem com os dados da Receita Federal.</p>
                                <button type="button" 
                                        onclick="updateFromApi()"
                                        class="mt-2 inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-yellow-800 bg-yellow-100 hover:bg-yellow-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                    🔁 Atualizar dados do cliente com os dados da API
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="flex space-x-3">
            <button type="button" 
                    onclick="verifyCpf()"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                Executar Verificação CPF
            </button>
            <?php if ($cpfConsultation): ?>
                <button type="button" 
                        onclick="showLastConsultation()"
                        class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    Ver Última Consulta
                </button>
            <?php endif; ?>
        </div>
    </div>

    <!-- Análise de Risco -->
    <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-medium text-gray-900">3. Análise de Risco (Opcional)</h2>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $riskStatus === 'consultado' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>">
                <?= ucfirst($riskStatus) ?>
            </span>
        </div>

        <form id="riskAnalysisForm" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" 
                               name="dividas_bancarias" 
                               value="1"
                               <?= ($riskAnalysis && $riskAnalysis['dividas_bancarias']) ? 'checked' : '' ?>
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">Dívidas Bancárias</span>
                    </label>
                </div>
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" 
                               name="cheque_sem_fundo" 
                               value="1"
                               <?= ($riskAnalysis && $riskAnalysis['cheque_sem_fundo']) ? 'checked' : '' ?>
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">Cheque sem Fundo</span>
                    </label>
                </div>
                <div>
                    <label class="flex items-center">
                        <input type="checkbox" 
                               name="protesto_nacional" 
                               value="1"
                               <?= ($riskAnalysis && $riskAnalysis['protesto_nacional']) ? 'checked' : '' ?>
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">Protesto Nacional</span>
                    </label>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Score</label>
                    <input type="number" 
                           name="score" 
                           min="0" 
                           max="1000"
                           value="<?= $riskAnalysis['score'] ?? '' ?>"
                           placeholder="0-1000"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Recomendação Serasa</label>
                <textarea name="recomendacao_serasa" 
                          rows="3"
                          placeholder="Observações e recomendações..."
                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50"><?= $riskAnalysis['recomendacao_serasa'] ?? '' ?></textarea>
            </div>
            <div>
                <button type="button" 
                        onclick="saveRiskAnalysis()"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Salvar Análise
                </button>
            </div>
        </form>
    </div>

    <!-- Resumo da Verificação -->
    <div class="bg-white shadow-sm rounded-lg p-6">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Resumo da Verificação</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="text-center">
                <div class="text-2xl font-bold <?= $visualStatus === 'aprovado' ? 'text-green-600' : ($visualStatus === 'reprovado' ? 'text-red-600' : 'text-yellow-600') ?>">
                    <?= ucfirst($visualStatus) ?>
                </div>
                <div class="text-sm text-gray-600">Documentos (Obrigatórios)</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold <?= $cpfStatus === 'aprovado' ? 'text-green-600' : ($cpfStatus === 'reprovado' ? 'text-red-600' : 'text-yellow-600') ?>">
                    <?= ucfirst($cpfStatus) ?>
                </div>
                <div class="text-sm text-gray-600">CPF (Obrigatório)</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-bold <?= $riskStatus === 'consultado' ? 'text-green-600' : 'text-yellow-600' ?>">
                    <?= ucfirst($riskStatus) ?>
                </div>
                <div class="text-sm text-gray-600">Serasa (Opcional)</div>
            </div>
        </div>

        <!-- Status de Elegibilidade -->
        <div class="text-center p-6 rounded-lg <?= $isEligible ? 'bg-green-50 border border-green-200' : 'bg-yellow-50 border border-yellow-200' ?>">
            <?php if ($isEligible): ?>
                <svg class="mx-auto h-12 w-12 text-green-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-lg font-medium text-green-800 mb-2">Cliente Liberado para Empréstimos</h3>
                <p class="text-sm text-green-600">Todas as verificações obrigatórias foram aprovadas.</p>
            <?php else: ?>
                <svg class="mx-auto h-12 w-12 text-yellow-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-lg font-medium text-yellow-800 mb-2">Verificações Pendentes</h3>
                <p class="text-sm text-yellow-600">Complete as verificações obrigatórias para liberar o cliente.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal para visualização de imagens -->
<div id="imageModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Documento</h3>
                <button type="button" onclick="closeImageModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="text-center">
                <img id="modalImage" src="" alt="" class="max-w-full h-auto rounded">
            </div>
        </div>
    </div>
</div>

<!-- Modal para exibir dados da última consulta CPF -->
<div id="consultationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-2/3 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Dados da Última Consulta CPF</h3>
                <button type="button" onclick="closeConsultationModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div id="consultationData" class="space-y-4">
                <!-- Dados serão carregados aqui via JavaScript -->
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
const clientId = <?= $client['id'] ?>;

// Função para normalizar string (remover acentos, espaços extras, minúsculo)
function normalizeString(str) {
    if (!str) return '';
    return str.toLowerCase()
              .trim()
              .normalize('NFD')
              .replace(/[\u0300-\u036f]/g, '') // Remove acentos
              .replace(/\s+/g, ' '); // Remove espaços extras
}

// Função para calcular dados divergentes em tempo real
function calcularDadosDivergentes(clientData, apiData) {
    if (!apiData.nome && !apiData.nascimento) {
        return false; // Sem dados da API para comparar
    }
    
    let divergente = false;
    
    // Comparar nomes
    if (apiData.nome) {
        const clienteNome = normalizeString(clientData.nome);
        const apiNome = normalizeString(apiData.nome);
        if (clienteNome !== apiNome) {
            divergente = true;
        }
    }
    
    // Comparar datas
    if (apiData.nascimento) {
        const clienteData = clientData.nascimento; // Já está em dd/mm/yyyy
        const apiData_nascimento = apiData.nascimento; // Já está em dd/mm/yyyy
        if (clienteData !== apiData_nascimento) {
            divergente = true;
        }
    }
    
    return divergente;
}

// Função para verificação visual
function verifyVisual(action) {
    fetch(`<?= base_url('/clients/verify/visual/') ?>${clientId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=${action}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert('Erro: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro na comunicação com o servidor');
    });
}

// Função para verificação de CPF
function verifyCpf() {
    const button = event.target;
    button.disabled = true;
    button.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Consultando...';

    fetch(`<?= base_url('/clients/verify/cpf/') ?>${clientId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert('Erro: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro na comunicação com o servidor');
    })
    .finally(() => {
        button.disabled = false;
        button.innerHTML = '<svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>Executar Verificação CPF';
    });
}

// Função para atualizar dados do cliente com API
function updateFromApi() {
    if (!confirm('Deseja atualizar os dados do cliente com as informações da API?')) {
        return;
    }

    fetch(`<?= base_url('/clients/update-from-api/') ?>${clientId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert('Erro: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro na comunicação com o servidor');
    });
}

// Função para salvar análise de risco
function saveRiskAnalysis() {
    const form = document.getElementById('riskAnalysisForm');
    const formData = new FormData(form);

    fetch(`<?= base_url('/clients/verify/risk/') ?>${clientId}`, {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert('Erro: ' + data.message);
            if (data.errors) {
                console.error('Erros de validação:', data.errors);
            }
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro na comunicação com o servidor');
    });
}

// Função para mostrar última consulta
function showLastConsultation() {
    const consultationData = <?= json_encode($cpfConsultation) ?>;
    
    if (!consultationData) {
        alert('Nenhuma consulta encontrada');
        return;
    }
    
    const rawData = JSON.parse(consultationData.raw_json || '{}');
    const clientData = {
        nome: '<?= esc($client['full_name']) ?>',
        nascimento: '<?= date('d/m/Y', strtotime($client['birth_date'])) ?>'
    };
    
    // Calcular dados divergentes em tempo real
    const dadosDivergentes = calcularDadosDivergentes(clientData, rawData);
    
    const html = `
        <div class="bg-gray-50 p-4 rounded-lg mb-4">
            <h4 class="font-medium text-gray-900 mb-2">Informações da Consulta</h4>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="font-medium text-gray-700">Data/Hora:</span>
                    <span class="text-gray-900">${new Date(consultationData.created_at).toLocaleString('pt-BR')}</span>
                </div>
                <div>
                    <span class="font-medium text-gray-700">Status:</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${consultationData.status === 'aprovado' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">${consultationData.status}</span>
                </div>
            </div>
        </div>
        
        <div class="bg-white border rounded-lg p-4 mb-4">
            <h4 class="font-medium text-gray-900 mb-3">Dados da Receita Federal</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                <!-- Coluna da Esquerda -->
                <div class="space-y-3">
                    <div>
                        <span class="font-medium text-gray-700">CPF Válido:</span>
                        <span class="ml-2 ${consultationData.cpf_valido ? 'text-green-600' : 'text-red-600'}">
                            ${consultationData.cpf_valido ? 'Sim' : 'Não'}
                            ${!consultationData.cpf_valido && (consultationData.codigo_erro || consultationData.mensagem_erro) ?
                                ` (${consultationData.codigo_erro ? 'Código: ' + consultationData.codigo_erro : ''}${consultationData.codigo_erro && consultationData.mensagem_erro ? ', ' : ''}${consultationData.mensagem_erro ? 'Erro: ' + consultationData.mensagem_erro : ''})` : ''}
                        </span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Óbito:</span>
                        <span class="ml-2 ${consultationData.obito ? 'text-red-600' : 'text-green-600'}">
                            ${consultationData.obito ? 'Sim' : 'Não'}
                            ${consultationData.obito && consultationData.ano_obito ? ` (${consultationData.ano_obito})` : ''}
                        </span>
                    </div>
                    <div>
                        <span class="font-medium text-gray-700">Dados Divergentes:</span>
                        <span class="ml-2 ${dadosDivergentes ? 'text-red-600' : 'text-green-600'}">${dadosDivergentes ? 'Sim' : 'Não'}</span>
                    </div>
                </div>
                
                <!-- Coluna da Direita -->
                <div class="space-y-3">
                    ${rawData.nome ? `
                    <div>
                        <span class="font-medium text-gray-700">Nome na Receita:</span>
                        <span class="ml-2 text-gray-900">${rawData.nome}</span>
                    </div>
                    ` : ''}
                    ${rawData.nascimento ? `
                    <div>
                        <span class="font-medium text-gray-700">Data de Nascimento na Receita:</span>
                        <span class="ml-2 text-gray-900">${rawData.nascimento}</span>
                    </div>
                    ` : ''}
                    ${rawData.situacao ? `
                    <div>
                        <span class="font-medium text-gray-700">Situação:</span>
                        <span class="ml-2 text-gray-900">${rawData.situacao}</span>
                    </div>
                    ` : ''}
                </div>
            </div>
        </div>
        
        ${dadosDivergentes ? `
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
            <h4 class="font-medium text-yellow-800 mb-3">Comparação de Dados</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <h5 class="font-medium text-yellow-700 mb-2">Dados do Cliente</h5>
                    <div class="space-y-1">
                        <div><span class="font-medium">Nome:</span> ${clientData.nome}</div>
                        <div><span class="font-medium">Nascimento:</span> ${clientData.nascimento}</div>
                    </div>
                </div>
                <div>
                    <h5 class="font-medium text-yellow-700 mb-2">Dados da Receita Federal</h5>
                    <div class="space-y-1">
                        <div><span class="font-medium">Nome:</span> ${rawData.nome || 'N/A'}</div>
                        <div><span class="font-medium">Nascimento:</span> ${rawData.nascimento || 'N/A'}</div>
                    </div>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-yellow-200">
                <button type="button"
                        onclick="updateFromApi(); closeConsultationModal();"
                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-yellow-800 bg-yellow-100 hover:bg-yellow-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                    🔁 Atualizar dados do cliente com os dados da API
                </button>
            </div>
        </div>
        ` : ''}
        
        ${consultationData.motivo_reprovacao ? `
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-4">
            <h4 class="font-medium text-red-800 mb-2">Motivo da Reprovação</h4>
            <p class="text-sm text-red-700">${consultationData.motivo_reprovacao}</p>
        </div>
        ` : ''}
        
        <div class="mt-4">
            <details class="bg-gray-50 rounded-lg p-4">
                <summary class="font-medium text-gray-700 cursor-pointer">Ver JSON Completo da API</summary>
                <pre class="mt-2 text-xs text-gray-600 bg-white p-3 rounded border overflow-x-auto">${JSON.stringify(rawData, null, 2)}</pre>
            </details>
        </div>
    `;
    
    document.getElementById('consultationData').innerHTML = html;
    document.getElementById('consultationModal').classList.remove('hidden');
}

// Função para fechar modal de consulta
function closeConsultationModal() {
    document.getElementById('consultationModal').classList.add('hidden');
}

// Funções do modal de imagem
function openImageModal(imageSrc, title) {
    document.getElementById('modalImage').src = imageSrc;
    document.getElementById('modalTitle').textContent = title;
    document.getElementById('imageModal').classList.remove('hidden');
}

function closeImageModal() {
    document.getElementById('imageModal').classList.add('hidden');
}

// Fechar modal ao clicar fora
document.getElementById('imageModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImageModal();
    }
});

// Fechar modal de consulta ao clicar fora
document.getElementById('consultationModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeConsultationModal();
    }
});
</script>

<?= $this->endSection() ?>