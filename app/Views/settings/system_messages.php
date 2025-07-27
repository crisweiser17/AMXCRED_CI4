<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900"><?= $title ?></h1>
            <p class="text-gray-600 mt-1">Configure textos e mensagens exibidas no sistema</p>
        </div>
        <a href="<?= base_url('/settings') ?>" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Voltar
        </a>
    </div>

    <!-- Mensagens -->
    <?php if (session('success')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <?= session('success') ?>
        </div>
    <?php endif; ?>

    <?php if (session('error')): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?= session('error') ?>
        </div>
    <?php endif; ?>

    <!-- Formulário -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="p-6">
            <form action="<?= base_url('/settings/save-system-messages') ?>" method="POST" id="systemMessagesForm">
                <?= csrf_field() ?>
                
                <!-- Termos e Condições -->
                <div class="mb-6">
                    <label for="loan_terms_conditions" class="block text-sm font-medium text-gray-700 mb-2">
                        Termos e Condições do Empréstimo
                    </label>
                    <p class="text-sm text-gray-500 mb-3">
                        Este texto será exibido na página de aceite de empréstimo. Use o editor para formatar o conteúdo.
                    </p>
                    <!-- Campo oculto para armazenar o conteúdo -->
                    <input type="hidden" id="loan_terms_conditions_hidden" name="loan_terms_conditions" value="<?= esc($messages['loan_terms_conditions']) ?>">
                    
                    <!-- Editor QuillJS -->
                    <div class="border border-gray-300 rounded-lg">
                        <div id="loan_terms_conditions" style="height: 300px;"></div>
                    </div>
                </div>

                <!-- Mensagens de Sucesso na Aceitação -->
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Mensagens de Sucesso na Aceitação</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="loan_acceptance_success_title" class="block text-sm font-medium text-gray-700 mb-2">
                                Título - Empréstimo Aceito
                            </label>
                            <input type="text" id="loan_acceptance_success_title" name="loan_acceptance_success_title" 
                                   value="<?= esc($messages['loan_acceptance_success_title'] ?? 'Empréstimo Aceito!') ?>"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <div>
                            <label for="loan_acceptance_success_message" class="block text-sm font-medium text-gray-700 mb-2">
                                Mensagem - Empréstimo Aceito
                            </label>
                            <input type="text" id="loan_acceptance_success_message" name="loan_acceptance_success_message" 
                                   value="<?= esc($messages['loan_acceptance_success_message'] ?? 'Seu empréstimo foi aceito com sucesso.') ?>"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <label for="loan_acceptance_success_next_steps" class="block text-sm font-medium text-gray-700 mb-2">
                            Próximos Passos - Empréstimo Aceito
                        </label>
                        <textarea id="loan_acceptance_success_next_steps" name="loan_acceptance_success_next_steps" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Aguarde o financiamento do seu empréstimo..."><?= esc($messages['loan_acceptance_success_next_steps'] ?? 'Aguarde o financiamento do seu empréstimo. Você será notificado quando o valor estiver disponível.') ?></textarea>
                    </div>
                </div>

                <!-- Mensagens de Recusa -->
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Mensagens de Recusa</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="loan_rejection_success_title" class="block text-sm font-medium text-gray-700 mb-2">
                                Título - Empréstimo Recusado
                            </label>
                            <input type="text" id="loan_rejection_success_title" name="loan_rejection_success_title" 
                                   value="<?= esc($messages['loan_rejection_success_title'] ?? 'Empréstimo Recusado') ?>"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <div>
                            <label for="loan_rejection_success_message" class="block text-sm font-medium text-gray-700 mb-2">
                                Mensagem - Empréstimo Recusado
                            </label>
                            <input type="text" id="loan_rejection_success_message" name="loan_rejection_success_message" 
                                   value="<?= esc($messages['loan_rejection_success_message'] ?? 'Empréstimo recusado. Obrigado pelo seu tempo.') ?>"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                </div>

                <!-- Mensagens de Erro -->
                <div class="mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Mensagens de Erro</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="loan_acceptance_error_title" class="block text-sm font-medium text-gray-700 mb-2">
                                Título - Erro na Aceitação
                            </label>
                            <input type="text" id="loan_acceptance_error_title" name="loan_acceptance_error_title" 
                                   value="<?= esc($messages['loan_acceptance_error_title'] ?? 'Erro na Aceitação') ?>"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <div>
                            <label for="loan_acceptance_error_message" class="block text-sm font-medium text-gray-700 mb-2">
                                Mensagem - Erro na Aceitação
                            </label>
                            <input type="text" id="loan_acceptance_error_message" name="loan_acceptance_error_message" 
                                   value="<?= esc($messages['loan_acceptance_error_message'] ?? 'Não foi possível aceitar o empréstimo.') ?>"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>
                </div>

                <!-- Botões -->
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="resetToDefault()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        <i class="fas fa-undo mr-2"></i>Restaurar Padrão
                    </button>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        <i class="fas fa-save mr-2"></i>Salvar Configurações
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- QuillJS CDN -->
<link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
<script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>

<script>
// Inicializar QuillJS
var quill = new Quill('#loan_terms_conditions', {
    theme: 'snow',
    modules: {
        toolbar: [
            [{ 'header': [1, 2, 3, false] }],
            ['bold', 'italic', 'underline'],
            [{ 'color': [] }, { 'background': [] }],
            [{ 'align': [] }],
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            [{ 'indent': '-1'}, { 'indent': '+1' }],
            ['link'],
            ['clean']
        ]
    },
    placeholder: 'Digite os termos e condições...',
    formats: ['header', 'bold', 'italic', 'underline', 'color', 'background', 'align', 'list', 'indent', 'link']
});

// Definir conteúdo inicial se existir
var initialContent = document.querySelector('#loan_terms_conditions_hidden').value;
if (initialContent) {
    quill.root.innerHTML = initialContent;
}

// Função para restaurar conteúdo padrão
function resetToDefault() {
    if (confirm('Tem certeza que deseja restaurar o conteúdo padrão? Todas as alterações serão perdidas.')) {
        const defaultContent = `<ul>
            <li>Ao aceitar este empréstimo, você concorda em pagar o valor total conforme especificado.</li>
            <li>O valor de cada parcela será cobrado mensalmente.</li>
            <li>O não pagamento das parcelas pode resultar em cobrança de juros e multas.</li>
            <li>Você tem o direito de quitar antecipadamente o empréstimo.</li>
            <li>Este empréstimo está sujeito às leis brasileiras de proteção ao consumidor.</li>
        </ul>`;
        
        quill.root.innerHTML = defaultContent;
        // Atualizar campo oculto
        document.getElementById('loan_terms_conditions_hidden').value = defaultContent;
    }
}

// Atualizar campo oculto quando o conteúdo do Quill mudar
quill.on('text-change', function() {
    document.getElementById('loan_terms_conditions_hidden').value = quill.root.innerHTML;
});

// Validação do formulário
document.getElementById('systemMessagesForm').addEventListener('submit', function(e) {
    // Garantir que o conteúdo do Quill seja salvo no campo oculto
    document.getElementById('loan_terms_conditions_hidden').value = quill.root.innerHTML;
    
    const content = document.getElementById('loan_terms_conditions_hidden').value.trim();
    
    if (!content || content === '<p><br></p>') {
        e.preventDefault();
        alert('Por favor, preencha os termos e condições.');
        return false;
    }
});
</script>

<?= $this->endSection() ?>