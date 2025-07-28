<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('content') ?>
<div class="max-w-4xl mx-auto">
    <!-- Page Header -->
    <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Informações da Empresa</h1>
                <p class="mt-1 text-sm text-gray-600">Configure as informações que aparecerão no layout público</p>
            </div>
            <a href="<?= base_url('/settings') ?>" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Voltar
            </a>
        </div>
    </div>

    <!-- Success Message -->
    <div id="success-message" class="hidden mb-6 bg-green-50 border border-green-200 rounded-md p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-green-800">Configurações salvas com sucesso!</p>
            </div>
        </div>
    </div>

    <!-- Error Message -->
    <div id="error-message" class="hidden mb-6 bg-red-50 border border-red-200 rounded-md p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-red-800" id="error-text">Erro ao salvar configurações.</p>
            </div>
        </div>
    </div>

    <!-- Company Info Form -->
    <div class="bg-white shadow-sm rounded-lg">
        <form id="company-info-form" class="space-y-6 p-6">
            <!-- Company Name -->
            <div>
                <label for="company_name" class="block text-sm font-medium text-gray-700 mb-2">
                    Nome da Empresa
                </label>
                <input type="text" 
                       id="company_name" 
                       name="company_name" 
                       value="<?= esc($company_name ?? 'AMX Cred') ?>"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                       placeholder="Digite o nome da empresa">
                <p class="mt-1 text-sm text-gray-500">Nome que aparecerá no cabeçalho do site público</p>
            </div>

            <!-- Company Slogan -->
            <div>
                <label for="company_slogan" class="block text-sm font-medium text-gray-700 mb-2">
                    Slogan da Empresa
                </label>
                <input type="text" 
                       id="company_slogan" 
                       name="company_slogan" 
                       value="<?= esc($company_slogan ?? 'Empréstimos Rápidos e Seguros') ?>"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                       placeholder="Digite o slogan da empresa">
                <p class="mt-1 text-sm text-gray-500">Slogan que aparecerá no cabeçalho do site público</p>
            </div>

            <!-- Company Email -->
            <div>
                <label for="company_email" class="block text-sm font-medium text-gray-700 mb-2">
                    E-mail de Contato
                </label>
                <input type="email" 
                       id="company_email" 
                       name="company_email" 
                       value="<?= esc($company_email ?? 'contato@amxcred.com.br') ?>"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                       placeholder="Digite o e-mail de contato">
                <p class="mt-1 text-sm text-gray-500">E-mail que aparecerá no rodapé do site público</p>
            </div>

            <!-- Company WhatsApp -->
            <div>
                <label for="company_whatsapp" class="block text-sm font-medium text-gray-700 mb-2">
                    WhatsApp
                </label>
                <input type="text" 
                       id="company_whatsapp" 
                       name="company_whatsapp" 
                       value="<?= esc($company_whatsapp ?? '(11) 99999-9999') ?>"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                       placeholder="Digite o número do WhatsApp">
                <p class="mt-1 text-sm text-gray-500">Número do WhatsApp que aparecerá no rodapé do site público</p>
            </div>

            <!-- Company Phone -->
            <div>
                <label for="company_phone" class="block text-sm font-medium text-gray-700 mb-2">
                    Telefone
                </label>
                <input type="text" 
                       id="company_phone" 
                       name="company_phone" 
                       value="<?= esc($company_phone ?? '(11) 3333-3333') ?>"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                       placeholder="Digite o telefone de contato">
                <p class="mt-1 text-sm text-gray-500">Telefone que aparecerá no rodapé do site público</p>
            </div>

            <!-- Company Logo URL -->
            <div>
                <label for="company_logo" class="block text-sm font-medium text-gray-700 mb-2">
                    URL do Logo
                </label>
                <input type="url" 
                       id="company_logo" 
                       name="company_logo" 
                       value="<?= esc($company_logo ?? '') ?>"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                       placeholder="https://exemplo.com/logo.png">
                <p class="mt-1 text-sm text-gray-500">URL da imagem do logo que aparecerá no cabeçalho (deixe em branco para usar apenas o nome)</p>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end pt-6 border-t border-gray-200">
                <button type="submit" 
                        id="save-btn"
                        class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed">
                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Salvar Configurações
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('company-info-form');
    const saveBtn = document.getElementById('save-btn');
    const successMessage = document.getElementById('success-message');
    const errorMessage = document.getElementById('error-message');
    const errorText = document.getElementById('error-text');

    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Disable button and show loading state
        saveBtn.disabled = true;
        saveBtn.innerHTML = `
            <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Salvando...
        `;
        
        // Hide previous messages
        successMessage.classList.add('hidden');
        errorMessage.classList.add('hidden');
        
        try {
            const formData = new FormData(form);
            
            const response = await fetch('<?= base_url('/settings/save-company-info') ?>', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            
            const result = await response.json();
            
            if (result.success) {
                successMessage.classList.remove('hidden');
                // Scroll to top to show success message
                window.scrollTo({ top: 0, behavior: 'smooth' });
            } else {
                errorText.textContent = result.message || 'Erro ao salvar configurações.';
                errorMessage.classList.remove('hidden');
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        } catch (error) {
            console.error('Error:', error);
            errorText.textContent = 'Erro de conexão. Tente novamente.';
            errorMessage.classList.remove('hidden');
            window.scrollTo({ top: 0, behavior: 'smooth' });
        } finally {
            // Restore button state
            saveBtn.disabled = false;
            saveBtn.innerHTML = `
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Salvar Configurações
            `;
        }
    });
});
</script>
<?= $this->endSection() ?>