<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-8">
    <!-- Page Header -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Editar Cliente</h1>
                <p class="text-gray-600 mt-1">Atualizar dados do cliente no sistema</p>
            </div>
            <a href="<?= base_url('/clients') ?>" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg inline-flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Voltar
            </a>
        </div>
    </div>

    <!-- Error Messages -->
    <?php if (session()->getFlashdata('errors')): ?>
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
            <div class="flex">
                <svg class="h-5 w-5 text-red-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Erro na validação</h3>
                    <ul class="mt-2 text-sm text-red-700 list-disc list-inside">
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Form -->
    <form id="client-edit-form" action="<?= base_url('/clients/update/' . $client['id']) ?>" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <input type="hidden" name="_method" value="PUT">

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
                    <label for="full_name" class="block text-sm font-medium text-gray-700 mb-1">
                        Nome Completo <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="full_name" name="full_name" value="<?= old('full_name', $client['full_name']) ?>"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Digite o nome completo" required>
                </div>

                <!-- CPF -->
                <div>
                    <label for="cpf" class="block text-sm font-medium text-gray-700 mb-1">
                        CPF <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="cpf" name="cpf" value="<?= old('cpf', $client['cpf']) ?>"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="000.000.000-00" required>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        Email <?= in_array('email', $requiredFields) ? '<span class="text-red-500">*</span>' : '' ?>
                    </label>
                    <input type="email" id="email" name="email" value="<?= old('email', $client['email']) ?>"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="exemplo@email.com" <?= in_array('email', $requiredFields) ? 'required' : '' ?>>
                </div>

                <!-- Telefone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                        Telefone <?= in_array('phone', $requiredFields) ? '<span class="text-red-500">*</span>' : '' ?>
                    </label>
                    <input type="text" id="phone" name="phone" value="<?= old('phone', $client['phone']) ?>"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="(00) 00000-0000" <?= in_array('phone', $requiredFields) ? 'required' : '' ?>>
                </div>

                <!-- Data de Nascimento -->
                <div>
                    <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-1">
                        Data de Nascimento <?= in_array('birth_date', $requiredFields) ? '<span class="text-red-500">*</span>' : '' ?>
                    </label>
                    <input type="date" id="birth_date" name="birth_date" value="<?= old('birth_date', $client['birth_date']) ?>"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           <?= in_array('birth_date', $requiredFields) ? 'required' : '' ?>>
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
                    <label for="pix_key_type" class="block text-sm font-medium text-gray-700 mb-1">
                        Tipo de Chave PIX <?= in_array('pix_key_type', $requiredFields) ? '<span class="text-red-500">*</span>' : '' ?>
                    </label>
                    <select id="pix_key_type" name="pix_key_type"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            <?= in_array('pix_key_type', $requiredFields) ? 'required' : '' ?>>
                        <option value="">Selecione o tipo...</option>
                        <?php foreach ($pixKeyTypeOptions as $value => $label): ?>
                            <option value="<?= esc($value) ?>" <?= old('pix_key_type', $client['pix_key_type']) === $value ? 'selected' : '' ?>>
                                <?= esc($label) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Chave PIX -->
                <div>
                    <label for="pix_key" class="block text-sm font-medium text-gray-700 mb-1">
                        Chave PIX <?= in_array('pix_key', $requiredFields) ? '<span class="text-red-500">*</span>' : '' ?>
                    </label>
                    <input type="text" id="pix_key" name="pix_key" value="<?= old('pix_key', $client['pix_key']) ?>"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Digite a chave PIX" <?= in_array('pix_key', $requiredFields) ? 'required' : '' ?>>
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
                    <label for="occupation" class="block text-sm font-medium text-gray-700 mb-1">
                        Ocupação <?= in_array('occupation', $requiredFields) ? '<span class="text-red-500">*</span>' : '' ?>
                    </label>
                    <select id="occupation" name="occupation"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            <?= in_array('occupation', $requiredFields) ? 'required' : '' ?>>
                        <option value="">Selecione uma ocupação...</option>
                        <?php foreach ($occupationOptions as $value => $label): ?>
                            <option value="<?= esc($value) ?>" <?= old('occupation', $client['occupation']) === $value ? 'selected' : '' ?>>
                                <?= esc($label) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Indústria/Setor -->
                <div>
                    <label for="industry" class="block text-sm font-medium text-gray-700 mb-1">
                        Indústria/Setor <?= in_array('industry', $requiredFields) ? '<span class="text-red-500">*</span>' : '' ?>
                    </label>
                    <select id="industry" name="industry"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            <?= in_array('industry', $requiredFields) ? 'required' : '' ?>>
                        <option value="">Selecione um setor...</option>
                        <?php foreach ($industryOptions as $value => $label): ?>
                            <option value="<?= esc($value) ?>" <?= old('industry', $client['industry']) === $value ? 'selected' : '' ?>>
                                <?= esc($label) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Tempo de Trabalho -->
                <div>
                    <label for="employment_duration" class="block text-sm font-medium text-gray-700 mb-1">
                        Tempo de Trabalho <?= in_array('employment_duration', $requiredFields) ? '<span class="text-red-500">*</span>' : '' ?>
                    </label>
                    <select id="employment_duration" name="employment_duration"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            <?= in_array('employment_duration', $requiredFields) ? 'required' : '' ?>>
                        <option value="">Selecione o tempo...</option>
                        <?php foreach ($employmentDurationOptions as $value => $label): ?>
                            <option value="<?= esc($value) ?>" <?= old('employment_duration', $client['employment_duration']) == $value ? 'selected' : '' ?>>
                                <?= esc($label) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Renda Mensal -->
                <div>
                    <label for="monthly_income" class="block text-sm font-medium text-gray-700 mb-1">
                        Renda Mensal <?= in_array('monthly_income', $requiredFields) ? '<span class="text-red-500">*</span>' : '' ?>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 text-sm">R$</span>
                        </div>
                        <input type="text" id="monthly_income" name="monthly_income" value="<?= old('monthly_income', $client['monthly_income']) ?>"
                               class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="0,00" <?= in_array('monthly_income', $requiredFields) ? 'required' : '' ?>>
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
                    <label for="zip_code" class="block text-sm font-medium text-gray-700 mb-1">
                        CEP <?= in_array('zip_code', $requiredFields) ? '<span class="text-red-500">*</span>' : '' ?>
                    </label>
                    <input type="text" id="zip_code" name="zip_code" value="<?= old('zip_code', $client['zip_code']) ?>"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="00000-000" <?= in_array('zip_code', $requiredFields) ? 'required' : '' ?>>
                </div>

                <!-- Rua -->
                <div class="md:col-span-2">
                    <label for="street" class="block text-sm font-medium text-gray-700 mb-1">
                        Rua <?= in_array('street', $requiredFields) ? '<span class="text-red-500">*</span>' : '' ?>
                    </label>
                    <input type="text" id="street" name="street" value="<?= old('street', $client['street']) ?>"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Nome da rua" <?= in_array('street', $requiredFields) ? 'required' : '' ?>>
                </div>

                <!-- Número -->
                <div>
                    <label for="number" class="block text-sm font-medium text-gray-700 mb-1">
                        Número <?= in_array('number', $requiredFields) ? '<span class="text-red-500">*</span>' : '' ?>
                    </label>
                    <input type="text" id="number" name="number" value="<?= old('number', $client['number']) ?>"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Número" <?= in_array('number', $requiredFields) ? 'required' : '' ?>>
                </div>

                <!-- Complemento -->
                <div>
                    <label for="complement" class="block text-sm font-medium text-gray-700 mb-1">
                        Complemento <?= in_array('complement', $requiredFields) ? '<span class="text-red-500">*</span>' : '' ?>
                    </label>
                    <input type="text" id="complement" name="complement" value="<?= old('complement', $client['complement']) ?>"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Apto, Bloco, etc." <?= in_array('complement', $requiredFields) ? 'required' : '' ?>>
                </div>

                <!-- Bairro -->
                <div>
                    <label for="neighborhood" class="block text-sm font-medium text-gray-700 mb-1">
                        Bairro <?= in_array('neighborhood', $requiredFields) ? '<span class="text-red-500">*</span>' : '' ?>
                    </label>
                    <input type="text" id="neighborhood" name="neighborhood" value="<?= old('neighborhood', $client['neighborhood']) ?>"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Nome do bairro" <?= in_array('neighborhood', $requiredFields) ? 'required' : '' ?>>
                </div>

                <!-- Cidade -->
                <div>
                    <label for="city" class="block text-sm font-medium text-gray-700 mb-1">
                        Cidade <?= in_array('city', $requiredFields) ? '<span class="text-red-500">*</span>' : '' ?>
                    </label>
                    <input type="text" id="city" name="city" value="<?= old('city', $client['city']) ?>"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Nome da cidade" <?= in_array('city', $requiredFields) ? 'required' : '' ?>>
                </div>

                <!-- UF -->
                <div>
                    <label for="state" class="block text-sm font-medium text-gray-700 mb-1">
                        UF <?= in_array('state', $requiredFields) ? '<span class="text-red-500">*</span>' : '' ?>
                    </label>
                    <select id="state" name="state"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            <?= in_array('state', $requiredFields) ? 'required' : '' ?>>
                        <option value="">Selecione o estado...</option>
                        <?php foreach ($stateOptions as $value => $label): ?>
                            <option value="<?= esc($value) ?>" <?= old('state', $client['state']) === $value ? 'selected' : '' ?>>
                                <?= esc($label) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
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
                            <label for="payslip_<?= $i ?>" class="block text-sm font-medium text-gray-700 mb-1">
                                <?= $i ?>º Holerite <?= in_array("payslip_$i", $requiredFields) ? '<span class="text-red-500">*</span>' : '' ?>
                            </label>
                            <?php if (!empty($client["payslip_$i"])): ?>
                                <div class="mb-2">
                                    <div class="relative inline-block">
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
                                    <div class="mt-2 flex gap-2">
                                        <button type="button"
                                                onclick="openDocumentModal(<?= $client['id'] ?>, 'payslip_<?= $i ?>', '<?= esc($client["payslip_$i"]) ?>')"
                                                class="text-xs bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-full inline-flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                            Ver
                                        </button>
                                        <button type="button"
                                                onclick="removeDocument(<?= $client['id'] ?>, 'payslip_<?= $i ?>')"
                                                class="text-xs bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-full inline-flex items-center">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1-1H8a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Remover
                                        </button>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <input type="file" id="payslip_<?= $i ?>" name="payslip_<?= $i ?>"
                                   class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                   accept="image/*,.pdf" <?= in_array("payslip_$i", $requiredFields) && empty($client["payslip_$i"]) ? 'required' : '' ?>>
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
                        <label for="id_front" class="block text-sm font-medium text-gray-700 mb-1">
                            RG Frente <?= in_array('id_front', $requiredFields) ? '<span class="text-red-500">*</span>' : '' ?>
                        </label>
                        <?php if (!empty($client['id_front'])): ?>
                            <div class="mb-2">
                                <div class="relative inline-block">
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
                                <div class="mt-2 flex gap-2">
                                    <button type="button"
                                            onclick="openDocumentModal(<?= $client['id'] ?>, 'id_front', '<?= esc($client['id_front']) ?>')"
                                            class="text-xs bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-full inline-flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        Ver
                                    </button>
                                    <button type="button"
                                            onclick="removeDocument(<?= $client['id'] ?>, 'id_front')"
                                            class="text-xs bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-full inline-flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1-1H8a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Remover
                                    </button>
                                </div>
                            </div>
                        <?php endif; ?>
                        <input type="file" id="id_front" name="id_front"
                               class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                               accept="image/*,.pdf" <?= in_array('id_front', $requiredFields) && empty($client['id_front']) ? 'required' : '' ?>>
                    </div>

                    <!-- RG Verso -->
                    <div>
                        <label for="id_back" class="block text-sm font-medium text-gray-700 mb-1">
                            RG Verso <?= in_array('id_back', $requiredFields) ? '<span class="text-red-500">*</span>' : '' ?>
                        </label>
                        <?php if (!empty($client['id_back'])): ?>
                            <div class="mb-2">
                                <div class="relative inline-block">
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
                                <div class="mt-2 flex gap-2">
                                    <button type="button"
                                            onclick="openDocumentModal(<?= $client['id'] ?>, 'id_back', '<?= esc($client['id_back']) ?>')"
                                            class="text-xs bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-full inline-flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        Ver
                                    </button>
                                    <button type="button"
                                            onclick="removeDocument(<?= $client['id'] ?>, 'id_back')"
                                            class="text-xs bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-full inline-flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1-1H8a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Remover
                                    </button>
                                </div>
                            </div>
                        <?php endif; ?>
                        <input type="file" id="id_back" name="id_back"
                               class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                               accept="image/*,.pdf" <?= in_array('id_back', $requiredFields) && empty($client['id_back']) ? 'required' : '' ?>>
                    </div>

                    <!-- Selfie -->
                    <div>
                        <label for="selfie" class="block text-sm font-medium text-gray-700 mb-1">
                            Selfie <?= in_array('selfie', $requiredFields) ? '<span class="text-red-500">*</span>' : '' ?>
                        </label>
                        <?php if (!empty($client['selfie'])): ?>
                            <div class="mb-2">
                                <div class="relative inline-block">
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
                                <div class="mt-2 flex gap-2">
                                    <button type="button"
                                            onclick="openDocumentModal(<?= $client['id'] ?>, 'selfie', '<?= esc($client['selfie']) ?>')"
                                            class="text-xs bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-full inline-flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        Ver
                                    </button>
                                    <button type="button"
                                            onclick="removeDocument(<?= $client['id'] ?>, 'selfie')"
                                            class="text-xs bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-full inline-flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1-1H8a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Remover
                                    </button>
                                </div>
                            </div>
                        <?php endif; ?>
                        <input type="file" id="selfie" name="selfie"
                               class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                               accept="image/*" <?= in_array('selfie', $requiredFields) && empty($client['selfie']) ? 'required' : '' ?>>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex items-center justify-between">
                <p class="text-sm text-gray-600">
                    <span class="text-red-500">*</span> Campos obrigatórios
                </p>
                <div class="flex space-x-3">
                    <a href="<?= base_url('/clients') ?>"
                       class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50">
                        Cancelar
                    </a>
                    <button type="submit" id="submit-button"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 inline-flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span id="submit-text">Atualizar Cliente</span>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- PIX Fields JavaScript -->
<script src="<?= base_url('js/pix-fields.js') ?>"></script>

<!-- File Preview JavaScript -->
<script src="<?= base_url('js/file-preview.js') ?>"></script>

<!-- Form JavaScript -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Máscaras para campos
    const cpfInput = document.getElementById('cpf');
    const phoneInput = document.getElementById('phone');
    const zipCodeInput = document.getElementById('zip_code');
    const monthlyIncomeInput = document.getElementById('monthly_income');

    // Máscara CPF
    cpfInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
        e.target.value = value;
    });

    // Máscara Telefone
    phoneInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length <= 10) {
            value = value.replace(/(\d{2})(\d)/, '($1) $2');
            value = value.replace(/(\d{4})(\d)/, '$1-$2');
        } else {
            value = value.replace(/(\d{2})(\d)/, '($1) $2');
            value = value.replace(/(\d{5})(\d)/, '$1-$2');
        }
        e.target.value = value;
    });

    // Máscara CEP
    zipCodeInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        value = value.replace(/(\d{5})(\d)/, '$1-$2');
        e.target.value = value;
    });

    // Máscara Renda Mensal
    monthlyIncomeInput.addEventListener('input', function(e) {
        let value = e.target.value.replace(/\D/g, '');
        value = (value / 100).toFixed(2) + '';
        value = value.replace(".", ",");
        value = value.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
        e.target.value = value;
    });

    // Busca automática de endereço por CEP
    zipCodeInput.addEventListener('blur', function() {
        const cep = this.value.replace(/\D/g, '');
        
        if (cep.length === 8) {
            const streetInput = document.getElementById('street');
            const neighborhoodInput = document.getElementById('neighborhood');
            const cityInput = document.getElementById('city');
            const stateSelect = document.getElementById('state');
            
            streetInput.value = 'Buscando...';
            neighborhoodInput.value = 'Buscando...';
            cityInput.value = 'Buscando...';
            
            fetch(`https://viacep.com.br/ws/${cep}/json/`)
                .then(response => response.json())
                .then(data => {
                    if (!data.erro) {
                        streetInput.value = data.logradouro || '';
                        neighborhoodInput.value = data.bairro || '';
                        cityInput.value = data.localidade || '';
                        stateSelect.value = data.uf || '';
                    } else {
                        alert('CEP não encontrado');
                        streetInput.value = '';
                        neighborhoodInput.value = '';
                        cityInput.value = '';
                    }
                })
                .catch(error => {
                    console.error('Erro ao buscar CEP:', error);
                    streetInput.value = '';
                    neighborhoodInput.value = '';
                    cityInput.value = '';
                    alert('Erro ao buscar CEP. Tente novamente.');
                });
        }
    });

    // Validação de CPF
    function validarCPF(cpf) {
        cpf = cpf.replace(/[^\d]+/g, '');
        if (cpf.length !== 11 || /^(\d)\1{10}$/.test(cpf)) return false;
        
        let soma = 0;
        for (let i = 0; i < 9; i++) {
            soma += parseInt(cpf.charAt(i)) * (10 - i);
        }
        let resto = 11 - (soma % 11);
        if (resto === 10 || resto === 11) resto = 0;
        if (resto !== parseInt(cpf.charAt(9))) return false;
        
        soma = 0;
        for (let i = 0; i < 10; i++) {
            soma += parseInt(cpf.charAt(i)) * (11 - i);
        }
        resto = 11 - (soma % 11);
        if (resto === 10 || resto === 11) resto = 0;
        return resto === parseInt(cpf.charAt(10));
    }

    // Validação em tempo real do CPF
    cpfInput.addEventListener('blur', function() {
        const cpf = this.value;
        if (cpf && !validarCPF(cpf)) {
            this.setCustomValidity('CPF inválido');
            this.classList.add('border-red-500');
        } else {
            this.setCustomValidity('');
            this.classList.remove('border-red-500');
        }
    });

    // Inicializar valores PIX após o carregamento do handler
    setTimeout(function() {
        if (window.pixFieldsHandler) {
            window.pixFieldsHandler.setValues({
                pixKeyType: '<?= esc($client['pix_key_type'] ?? '') ?>',
                pixKey: '<?= esc($client['pix_key'] ?? '') ?>'
            });
        }
    }, 100);

    // Validação do formulário antes do envio
    const form = document.getElementById('client-edit-form');
    const submitButton = document.getElementById('submit-button');
    const submitText = document.getElementById('submit-text');

    form.addEventListener('submit', function(e) {
        // Validar CPF
        const cpf = cpfInput.value;
        if (cpf && cpf.length > 0 && !validarCPF(cpf)) {
            e.preventDefault();
            alert('Por favor, insira um CPF válido.');
            cpfInput.focus();
            return;
        }

        // Validar campos PIX usando o handler reutilizável (apenas se preenchidos)
        if (window.pixFieldsHandler) {
            const pixValues = window.pixFieldsHandler.getValues();
            // Só valida se pelo menos um campo PIX foi preenchido
            if (pixValues.pixKeyType || pixValues.pixKey) {
                const pixValidation = window.pixFieldsHandler.validate();
                if (!pixValidation.isValid) {
                    e.preventDefault();
                    alert('Erro nos campos PIX: ' + pixValidation.errors.join(', '));
                    return;
                }
            }
        }

        // Converter renda mensal para formato decimal (apenas se não estiver vazio)
        const monthlyIncome = monthlyIncomeInput.value;
        if (monthlyIncome && monthlyIncome.trim() !== '') {
            // Remover formatação e converter para decimal
            let numericValue = monthlyIncome.replace(/\./g, '').replace(',', '.');
            // Se o valor já foi processado pela máscara (tem vírgula), não dividir por 100 novamente
            if (monthlyIncome.includes(',')) {
                numericValue = monthlyIncome.replace(/\./g, '').replace(',', '.');
            } else {
                // Se é um valor puro (sem formatação), manter como está
                numericValue = monthlyIncome;
            }
            monthlyIncomeInput.value = numericValue;
        }

        // Mostrar loading no botão
        submitButton.disabled = true;
        submitText.textContent = 'Atualizando...';
    });

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

    window.removeDocument = function(clientId, documentType) {
        const titles = {
            'payslip_1': '1º Holerite',
            'payslip_2': '2º Holerite',
            'payslip_3': '3º Holerite',
            'id_front': 'RG Frente',
            'id_back': 'RG Verso',
            'selfie': 'Selfie'
        };
        
        const documentName = titles[documentType] || 'documento';
        
        if (confirm(`Tem certeza que deseja remover o ${documentName}?`)) {
            fetch(`<?= base_url('/documents/delete/') ?>${clientId}/${documentType}`, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Recarregar página para atualizar interface
                    location.reload();
                } else {
                    alert('Erro ao remover documento: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Erro ao remover documento');
            });
        }
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