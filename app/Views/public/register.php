<?= $this->extend('layouts/public_layout') ?>

<?= $this->section('content') ?>
<div class="max-w-4xl mx-auto px-4">
    <!-- Page Header -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <div class="text-center">
            <h1 class="text-3xl font-bold text-gray-900">Cadastro de Cliente</h1>
            <p class="text-gray-600 mt-2">Preencha seus dados para solicitar um empréstimo</p>
            <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                <p class="text-sm text-blue-800">
                    <strong>Importante:</strong> Todos os campos marcados com * são obrigatórios. 
                    Após o envio, nossa equipe analisará sua solicitação em até 24 horas.
                </p>
            </div>
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

    <!-- Success Message -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
            <div class="flex">
                <svg class="h-5 w-5 text-green-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800"><?= session()->getFlashdata('success') ?></p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Error Message -->
    <?php if (session()->getFlashdata('error')): ?>
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
            <div class="flex">
                <svg class="h-5 w-5 text-red-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800"><?= session()->getFlashdata('error') ?></p>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Form -->
    <form id="public-register-form" action="<?= base_url('/register/store') ?>" method="POST" enctype="multipart/form-data">
        <?= csrf_field() ?>

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
                    <input type="text" id="full_name" name="full_name" value="<?= old('full_name') ?>"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Digite seu nome completo" required>
                </div>

                <!-- CPF -->
                <div>
                    <label for="cpf" class="block text-sm font-medium text-gray-700 mb-1">
                        CPF <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="cpf" name="cpf" value="<?= old('cpf') ?>"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="000.000.000-00" required>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" id="email" name="email" value="<?= old('email') ?>"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="exemplo@email.com" required>
                </div>

                <!-- Telefone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">
                        Telefone <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="phone" name="phone" value="<?= old('phone') ?>"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="(00) 00000-0000" required>
                </div>

                <!-- Data de Nascimento -->
                <div>
                    <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-1">
                        Data de Nascimento <span class="text-red-500">*</span>
                    </label>
                    <input type="date" id="birth_date" name="birth_date" value="<?= old('birth_date') ?>"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           required>
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
                        Ocupação <span class="text-red-500">*</span>
                    </label>
                    <select id="occupation" name="occupation"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required>
                        <option value="">Selecione sua ocupação...</option>
                        <?php foreach ($occupationOptions as $value => $label): ?>
                            <option value="<?= esc($value) ?>" <?= old('occupation') === $value ? 'selected' : '' ?>>
                                <?= esc($label) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Indústria/Setor -->
                <div>
                    <label for="industry" class="block text-sm font-medium text-gray-700 mb-1">
                        Indústria/Setor <span class="text-red-500">*</span>
                    </label>
                    <select id="industry" name="industry"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required>
                        <option value="">Selecione um setor...</option>
                        <?php foreach ($industryOptions as $value => $label): ?>
                            <option value="<?= esc($value) ?>" <?= old('industry') === $value ? 'selected' : '' ?>>
                                <?= esc($label) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Tempo de Trabalho -->
                <div>
                    <label for="employment_duration" class="block text-sm font-medium text-gray-700 mb-1">
                        Tempo de Trabalho <span class="text-red-500">*</span>
                    </label>
                    <select id="employment_duration" name="employment_duration"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required>
                        <option value="">Selecione o tempo...</option>
                        <?php foreach ($employmentDurationOptions as $value => $label): ?>
                            <option value="<?= esc($value) ?>" <?= old('employment_duration') === $value ? 'selected' : '' ?>>
                                <?= esc($label) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Renda Mensal -->
                <div>
                    <label for="monthly_income" class="block text-sm font-medium text-gray-700 mb-1">
                        Renda Mensal <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 text-sm">R$</span>
                        </div>
                        <input type="text" id="monthly_income" name="monthly_income" value="<?= old('monthly_income') ?>"
                               class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="0,00" required>
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
                    <label for="pix_key_type" class="block text-sm font-medium text-gray-700 mb-1">
                        Tipo de Chave PIX <span class="text-red-500">*</span>
                    </label>
                    <select id="pix_key_type" name="pix_key_type"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required>
                        <option value="">Selecione o tipo...</option>
                        <?php foreach ($pixKeyTypeOptions as $value => $label): ?>
                            <option value="<?= esc($value) ?>" <?= old('pix_key_type') === $value ? 'selected' : '' ?>>
                                <?= esc($label) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Chave PIX -->
                <div>
                    <label for="pix_key" class="block text-sm font-medium text-gray-700 mb-1">
                        Chave PIX <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="pix_key" name="pix_key" value="<?= old('pix_key') ?>"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Digite sua chave PIX" required>
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
                        CEP <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="zip_code" name="zip_code" value="<?= old('zip_code') ?>"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="00000-000" required>
                </div>

                <!-- Rua -->
                <div class="md:col-span-2">
                    <label for="street" class="block text-sm font-medium text-gray-700 mb-1">
                        Rua <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="street" name="street" value="<?= old('street') ?>"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Nome da rua" required>
                </div>

                <!-- Número -->
                <div>
                    <label for="number" class="block text-sm font-medium text-gray-700 mb-1">
                        Número <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="number" name="number" value="<?= old('number') ?>"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Número" required>
                </div>

                <!-- Complemento -->
                <div>
                    <label for="complement" class="block text-sm font-medium text-gray-700 mb-1">
                        Complemento
                    </label>
                    <input type="text" id="complement" name="complement" value="<?= old('complement') ?>"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Apto, Bloco, etc.">
                </div>

                <!-- Bairro -->
                <div>
                    <label for="neighborhood" class="block text-sm font-medium text-gray-700 mb-1">
                        Bairro <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="neighborhood" name="neighborhood" value="<?= old('neighborhood') ?>"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Nome do bairro" required>
                </div>

                <!-- Cidade -->
                <div>
                    <label for="city" class="block text-sm font-medium text-gray-700 mb-1">
                        Cidade <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="city" name="city" value="<?= old('city') ?>"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                           placeholder="Nome da cidade" required>
                </div>

                <!-- UF -->
                <div>
                    <label for="state" class="block text-sm font-medium text-gray-700 mb-1">
                        UF <span class="text-red-500">*</span>
                    </label>
                    <select id="state" name="state"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required>
                        <option value="">Selecione o estado...</option>
                        <?php foreach ($stateOptions as $value => $label): ?>
                            <option value="<?= esc($value) ?>" <?= old('state') === $value ? 'selected' : '' ?>>
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
                <p class="text-sm text-gray-600 mb-4">Envie os 3 últimos holerites ou comprovantes de renda (PDF ou imagem)</p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <?php for ($i = 1; $i <= 3; $i++): ?>
                        <div>
                            <label for="payslip_<?= $i ?>" class="block text-sm font-medium text-gray-700 mb-1">
                                <?= $i ?>º Holerite <span class="text-red-500">*</span>
                            </label>
                            <input type="file" id="payslip_<?= $i ?>" name="payslip_<?= $i ?>"
                                   class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                   accept="image/*,.pdf" required>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>

            <!-- Documento de Identidade -->
            <div class="mb-6">
                <h3 class="text-md font-medium text-gray-900 mb-3">Documento de Identidade</h3>
                <p class="text-sm text-gray-600 mb-4">Envie fotos nítidas da frente e verso do seu RG ou CNH</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- RG Frente -->
                    <div>
                        <label for="id_front" class="block text-sm font-medium text-gray-700 mb-1">
                            RG Frente <span class="text-red-500">*</span>
                        </label>
                        <input type="file" id="id_front" name="id_front"
                               class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                               accept="image/*,.pdf" required>
                    </div>

                    <!-- RG Verso -->
                    <div>
                        <label for="id_back" class="block text-sm font-medium text-gray-700 mb-1">
                            RG Verso <span class="text-red-500">*</span>
                        </label>
                        <input type="file" id="id_back" name="id_back"
                               class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                               accept="image/*,.pdf" required>
                    </div>
                </div>
            </div>

            <!-- Selfie -->
            <div>
                <h3 class="text-md font-medium text-gray-900 mb-3">Selfie</h3>
                <p class="text-sm text-gray-600 mb-4">Tire uma selfie segurando seu documento de identidade</p>
                <div class="max-w-md">
                    <label for="selfie" class="block text-sm font-medium text-gray-700 mb-1">
                        Selfie com Documento <span class="text-red-500">*</span>
                    </label>
                    <input type="file" id="selfie" name="selfie"
                           class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                           accept="image/*" required>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="bg-white shadow rounded-lg p-6">
            <div class="text-center">
                <button type="submit" id="submit-button"
                        class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 inline-flex items-center text-lg font-semibold transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span id="submit-text">Enviar Cadastro</span>
                </button>
                <p class="text-sm text-gray-600 mt-4">
                    Ao enviar este formulário, você concorda com nossos 
                    <a href="#" class="text-blue-600 hover:text-blue-800">termos de uso</a> e 
                    <a href="#" class="text-blue-600 hover:text-blue-800">política de privacidade</a>.
                </p>
            </div>
        </div>
    </form>
</div>

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

    // Validação do formulário antes do envio
    const form = document.getElementById('public-register-form');
    const submitButton = document.getElementById('submit-button');
    const submitText = document.getElementById('submit-text');

    form.addEventListener('submit', function(e) {
        // Validar CPF
        const cpf = cpfInput.value;
        if (!validarCPF(cpf)) {
            e.preventDefault();
            alert('Por favor, insira um CPF válido.');
            cpfInput.focus();
            return;
        }

        // Validar campos PIX usando o handler se disponível
        if (window.pixFieldsHandler) {
            const pixValidation = window.pixFieldsHandler.validate();
            if (!pixValidation.isValid) {
                e.preventDefault();
                alert('Erro nos campos PIX: ' + pixValidation.errors.join(', '));
                return;
            }
        }

        // Converter renda mensal para formato decimal
        const monthlyIncome = monthlyIncomeInput.value;
        if (monthlyIncome && monthlyIncome.trim() !== '') {
            let numericValue = monthlyIncome.replace(/\./g, '').replace(',', '.');
            if (monthlyIncome.includes(',')) {
                numericValue = monthlyIncome.replace(/\./g, '').replace(',', '.');
            } else {
                numericValue = monthlyIncome;
            }
            monthlyIncomeInput.value = numericValue;
        }

        // Mostrar loading
        submitButton.disabled = true;
        submitText.textContent = 'Enviando...';
        
        // Mostrar overlay de loading
        const loadingOverlay = document.getElementById('loading-overlay');
        if (loadingOverlay) {
            loadingOverlay.classList.remove('hidden');
        }
    });
});
</script>

<?= $this->endSection() ?>