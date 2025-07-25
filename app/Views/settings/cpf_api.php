<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto">
    <!-- Page Header -->
    <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Configurações da API CPF</h1>
                <p class="mt-1 text-sm text-gray-600">Configure os tokens e ambiente da API cpfcnpj.com.br</p>
            </div>
            <a href="<?= base_url('/settings') ?>" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Voltar
            </a>
        </div>
    </div>

    <!-- Configurações da API -->
    <div class="bg-white shadow-sm rounded-lg p-6">
        <form id="cpfApiForm" class="space-y-6">
            <!-- Ambiente -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Ambiente da API</label>
                <div class="space-y-2">
                    <label class="flex items-center">
                        <input type="radio" 
                               name="cpf_api_environment" 
                               value="test" 
                               <?= ($settings['cpf_api_environment'] ?? 'test') === 'test' ? 'checked' : '' ?>
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">
                            <span class="font-medium">Teste</span> - Para desenvolvimento e testes
                        </span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" 
                               name="cpf_api_environment" 
                               value="production" 
                               <?= ($settings['cpf_api_environment'] ?? 'test') === 'production' ? 'checked' : '' ?>
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">
                            <span class="font-medium">Produção</span> - Para uso em produção
                        </span>
                    </label>
                </div>
            </div>

            <!-- Token de Teste -->
            <div>
                <label for="cpf_api_test_token" class="block text-sm font-medium text-gray-700">Token de Teste</label>
                <div class="mt-1">
                    <input type="text" 
                           id="cpf_api_test_token" 
                           name="cpf_api_test_token" 
                           value="<?= esc($settings['cpf_api_test_token'] ?? '') ?>"
                           placeholder="Token para ambiente de teste"
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                </div>
                <p class="mt-1 text-xs text-gray-500">Token fornecido pela cpfcnpj.com.br para testes</p>
            </div>

            <!-- Token de Produção -->
            <div>
                <label for="cpf_api_production_token" class="block text-sm font-medium text-gray-700">Token de Produção</label>
                <div class="mt-1">
                    <input type="text" 
                           id="cpf_api_production_token" 
                           name="cpf_api_production_token" 
                           value="<?= esc($settings['cpf_api_production_token'] ?? '') ?>"
                           placeholder="Token para ambiente de produção"
                           class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                </div>
                <p class="mt-1 text-xs text-gray-500">Token fornecido pela cpfcnpj.com.br para produção</p>
            </div>

            <!-- URLs da API -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="cpf_api_test_url" class="block text-sm font-medium text-gray-700">URL de Teste</label>
                    <div class="mt-1">
                        <input type="url" 
                               id="cpf_api_test_url" 
                               name="cpf_api_test_url" 
                               value="<?= esc($settings['cpf_api_test_url'] ?? 'https://api.cpfcnpj.com.br/test') ?>"
                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    </div>
                </div>
                <div>
                    <label for="cpf_api_production_url" class="block text-sm font-medium text-gray-700">URL de Produção</label>
                    <div class="mt-1">
                        <input type="url" 
                               id="cpf_api_production_url" 
                               name="cpf_api_production_url" 
                               value="<?= esc($settings['cpf_api_production_url'] ?? 'https://api.cpfcnpj.com.br') ?>"
                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    </div>
                </div>
            </div>

            <!-- Status Atual -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h3 class="text-sm font-medium text-gray-900 mb-2">Status Atual</h3>
                <div class="space-y-1 text-sm text-gray-600">
                    <p><span class="font-medium">Ambiente:</span> 
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= ($settings['cpf_api_environment'] ?? 'test') === 'test' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' ?>">
                            <?= ucfirst($settings['cpf_api_environment'] ?? 'test') ?>
                        </span>
                    </p>
                    <p><span class="font-medium">Token Configurado:</span> 
                        <?php 
                        $currentEnv = $settings['cpf_api_environment'] ?? 'test';
                        $tokenKey = $currentEnv === 'test' ? 'cpf_api_test_token' : 'cpf_api_production_token';
                        $hasToken = !empty($settings[$tokenKey] ?? '');
                        ?>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $hasToken ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                            <?= $hasToken ? 'Sim' : 'Não' ?>
                        </span>
                    </p>
                </div>
            </div>

            <!-- Botões -->
            <div class="flex justify-end space-x-3">
                <button type="button" 
                        onclick="testConnection()"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Testar Conexão
                </button>
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Salvar Configurações
                </button>
            </div>
        </form>
    </div>

    <!-- Documentação -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mt-6">
        <h3 class="text-lg font-medium text-blue-900 mb-3">📚 Documentação da API</h3>
        <div class="space-y-2 text-sm text-blue-800">
            <p><strong>Site oficial:</strong> <a href="https://www.cpfcnpj.com.br/" target="_blank" class="underline hover:text-blue-600">https://www.cpfcnpj.com.br/</a></p>
            <p><strong>Documentação:</strong> Consulte a documentação oficial para obter seus tokens de API</p>
            <p><strong>Pacotes utilizados:</strong> 13 (consulta básica) e 8 (dados complementares)</p>
            <p><strong>Ambiente de teste:</strong> Use o token de teste fornecido para desenvolvimento</p>
            <p><strong>Ambiente de produção:</strong> Configure seu token de produção antes de usar em produção</p>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
// Salvar configurações
document.getElementById('cpfApiForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const button = e.target.querySelector('button[type="submit"]');
    const originalText = button.innerHTML;
    
    button.disabled = true;
    button.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Salvando...';
    
    fetch('<?= base_url('/settings/save-cpf-api') ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Configurações salvas com sucesso!');
            location.reload();
        } else {
            alert('Erro ao salvar: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro na comunicação com o servidor');
    })
    .finally(() => {
        button.disabled = false;
        button.innerHTML = originalText;
    });
});

// Testar conexão
function testConnection() {
    const button = event.target;
    const originalText = button.innerHTML;
    
    button.disabled = true;
    button.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Testando...';
    
    fetch('<?= base_url('/settings/test-cpf-api') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('✅ Conexão testada com sucesso!\n\nDetalhes:\n' + data.message);
        } else {
            alert('❌ Erro no teste de conexão:\n\n' + data.message);
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('❌ Erro na comunicação com o servidor');
    })
    .finally(() => {
        button.disabled = false;
        button.innerHTML = originalText;
    });
}
</script>

<?= $this->endSection() ?>