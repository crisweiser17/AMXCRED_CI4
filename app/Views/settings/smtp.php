<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto">
    <!-- Page Header -->
    <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Configurações SMTP</h1>
                <p class="mt-1 text-sm text-gray-600">Configure as configurações de e-mail SMTP para envio de mensagens</p>
            </div>
            <a href="<?= base_url('/settings') ?>" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Voltar
            </a>
        </div>
    </div>

    <!-- Configurações SMTP -->
    <div class="bg-white shadow-sm rounded-lg p-6">
        <form id="smtpForm" class="space-y-6">
            <!-- Protocolo -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Protocolo de E-mail</label>
                <div class="space-y-2">
                    <label class="flex items-center">
                        <input type="radio" 
                               name="protocol" 
                               value="mail" 
                               <?= ($settings['protocol'] ?? 'mail') === 'mail' ? 'checked' : '' ?>
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">
                            <span class="font-medium">Mail</span> - Função mail() do PHP
                        </span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" 
                               name="protocol" 
                               value="sendmail" 
                               <?= ($settings['protocol'] ?? 'mail') === 'sendmail' ? 'checked' : '' ?>
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">
                            <span class="font-medium">Sendmail</span> - Comando sendmail do sistema
                        </span>
                    </label>
                    <label class="flex items-center">
                        <input type="radio" 
                               name="protocol" 
                               value="smtp" 
                               <?= ($settings['protocol'] ?? 'mail') === 'smtp' ? 'checked' : '' ?>
                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        <span class="ml-2 text-sm text-gray-700">
                            <span class="font-medium">SMTP</span> - Servidor SMTP externo
                        </span>
                    </label>
                </div>
            </div>

            <!-- Configurações Básicas -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="fromEmail" class="block text-sm font-medium text-gray-700">E-mail Remetente</label>
                    <div class="mt-1">
                        <input type="email" 
                               id="fromEmail" 
                               name="fromEmail" 
                               value="<?= esc($settings['fromEmail'] ?? '') ?>"
                               placeholder="noreply@exemplo.com"
                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    </div>
                    <p class="mt-1 text-xs text-gray-500">E-mail que aparecerá como remetente</p>
                </div>
                <div>
                    <label for="fromName" class="block text-sm font-medium text-gray-700">Nome Remetente</label>
                    <div class="mt-1">
                        <input type="text" 
                               id="fromName" 
                               name="fromName" 
                               value="<?= esc($settings['fromName'] ?? '') ?>"
                               placeholder="Sistema AMX"
                               class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    </div>
                    <p class="mt-1 text-xs text-gray-500">Nome que aparecerá como remetente</p>
                </div>
            </div>

            <!-- Configurações SMTP (visível apenas quando SMTP estiver selecionado) -->
            <div id="smtpSettings" class="space-y-6" style="display: <?= ($settings['protocol'] ?? 'mail') === 'smtp' ? 'block' : 'none' ?>">
                <div class="border-t pt-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Configurações do Servidor SMTP</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="SMTPHost" class="block text-sm font-medium text-gray-700">Servidor SMTP</label>
                            <div class="mt-1">
                                <input type="text" 
                                       id="SMTPHost" 
                                       name="SMTPHost" 
                                       value="<?= esc($settings['SMTPHost'] ?? '') ?>"
                                       placeholder="smtp.gmail.com"
                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            </div>
                        </div>
                        <div>
                            <label for="SMTPPort" class="block text-sm font-medium text-gray-700">Porta SMTP</label>
                            <div class="mt-1">
                                <input type="number" 
                                       id="SMTPPort" 
                                       name="SMTPPort" 
                                       value="<?= esc($settings['SMTPPort'] ?? '587') ?>"
                                       placeholder="587"
                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                        <div>
                            <label for="SMTPUser" class="block text-sm font-medium text-gray-700">Usuário SMTP</label>
                            <div class="mt-1">
                                <input type="text" 
                                       id="SMTPUser" 
                                       name="SMTPUser" 
                                       value="<?= esc($settings['SMTPUser'] ?? '') ?>"
                                       placeholder="usuario@gmail.com"
                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            </div>
                        </div>
                        <div>
                            <label for="SMTPPass" class="block text-sm font-medium text-gray-700">Senha SMTP</label>
                            <div class="mt-1">
                                <input type="password" 
                                       id="SMTPPass" 
                                       name="SMTPPass" 
                                       value="<?= esc($settings['SMTPPass'] ?? '') ?>"
                                       placeholder="••••••••"
                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                        <div>
                            <label for="SMTPCrypto" class="block text-sm font-medium text-gray-700">Criptografia</label>
                            <div class="mt-1">
                                <select id="SMTPCrypto" 
                                        name="SMTPCrypto" 
                                        class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <option value="" <?= ($settings['SMTPCrypto'] ?? 'tls') === '' ? 'selected' : '' ?>>Nenhuma</option>
                                    <option value="tls" <?= ($settings['SMTPCrypto'] ?? 'tls') === 'tls' ? 'selected' : '' ?>>TLS</option>
                                    <option value="ssl" <?= ($settings['SMTPCrypto'] ?? 'tls') === 'ssl' ? 'selected' : '' ?>>SSL</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label for="SMTPTimeout" class="block text-sm font-medium text-gray-700">Timeout (segundos)</label>
                            <div class="mt-1">
                                <input type="number" 
                                       id="SMTPTimeout" 
                                       name="SMTPTimeout" 
                                       value="<?= esc($settings['SMTPTimeout'] ?? '5') ?>"
                                       placeholder="5"
                                       class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            </div>
                        </div>
                        <div class="flex items-center">
                            <label class="flex items-center mt-6">
                                <input type="checkbox" 
                                       name="SMTPKeepAlive" 
                                       value="1"
                                       <?= ($settings['SMTPKeepAlive'] ?? false) ? 'checked' : '' ?>
                                       class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700">Manter conexão ativa</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Configurações Avançadas -->
            <div class="border-t pt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Configurações Avançadas</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="mailType" class="block text-sm font-medium text-gray-700">Tipo de E-mail</label>
                        <div class="mt-1">
                            <select id="mailType" 
                                    name="mailType" 
                                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                <option value="text" <?= ($settings['mailType'] ?? 'text') === 'text' ? 'selected' : '' ?>>Texto</option>
                                <option value="html" <?= ($settings['mailType'] ?? 'text') === 'html' ? 'selected' : '' ?>>HTML</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label for="charset" class="block text-sm font-medium text-gray-700">Charset</label>
                        <div class="mt-1">
                            <input type="text" 
                                   id="charset" 
                                   name="charset" 
                                   value="<?= esc($settings['charset'] ?? 'UTF-8') ?>"
                                   placeholder="UTF-8"
                                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Status Atual -->
            <div class="bg-gray-50 rounded-lg p-4">
                <h3 class="text-sm font-medium text-gray-900 mb-2">Status Atual</h3>
                <div class="space-y-1 text-sm text-gray-600">
                    <p><span class="font-medium">Protocolo:</span> 
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            <?= ucfirst($settings['protocol'] ?? 'mail') ?>
                        </span>
                    </p>
                    <p><span class="font-medium">E-mail Remetente:</span> 
                        <span class="<?= !empty($settings['fromEmail'] ?? '') ? 'text-green-600' : 'text-red-600' ?>">
                            <?= !empty($settings['fromEmail'] ?? '') ? esc($settings['fromEmail']) : 'Não configurado' ?>
                        </span>
                    </p>
                    <?php if (($settings['protocol'] ?? 'mail') === 'smtp'): ?>
                    <p><span class="font-medium">Servidor SMTP:</span> 
                        <span class="<?= !empty($settings['SMTPHost'] ?? '') ? 'text-green-600' : 'text-red-600' ?>">
                            <?= !empty($settings['SMTPHost'] ?? '') ? esc($settings['SMTPHost']) : 'Não configurado' ?>
                        </span>
                    </p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Botões -->
            <div class="flex justify-end space-x-3">
                <button type="button" 
                        onclick="testEmail()"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    Enviar E-mail Teste
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
        <h3 class="text-lg font-medium text-blue-900 mb-3">📧 Configuração de E-mail</h3>
        <div class="space-y-2 text-sm text-blue-800">
            <p><strong>Mail:</strong> Usa a função mail() do PHP (requer configuração do servidor)</p>
            <p><strong>Sendmail:</strong> Usa o comando sendmail do sistema operacional</p>
            <p><strong>SMTP:</strong> Conecta a um servidor SMTP externo (Gmail, Outlook, etc.)</p>
            <p><strong>Portas comuns:</strong> 25 (não criptografado), 587 (TLS), 465 (SSL)</p>
            <p><strong>Gmail:</strong> smtp.gmail.com:587 com TLS (requer senha de app)</p>
            <p><strong>Outlook:</strong> smtp-mail.outlook.com:587 com TLS</p>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
// Mostrar/ocultar configurações SMTP
document.querySelectorAll('input[name="protocol"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const smtpSettings = document.getElementById('smtpSettings');
        if (this.value === 'smtp') {
            smtpSettings.style.display = 'block';
        } else {
            smtpSettings.style.display = 'none';
        }
    });
});

// Salvar configurações
document.getElementById('smtpForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const button = e.target.querySelector('button[type="submit"]');
    const originalText = button.innerHTML;
    
    button.disabled = true;
    button.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Salvando...';
    
    fetch('<?= base_url('/settings/save-smtp') ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Configurações SMTP salvas com sucesso!');
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

// Enviar e-mail de teste
function testEmail() {
    const button = event.target;
    const originalText = button.innerHTML;
    
    button.disabled = true;
    button.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Enviando...';
    
    const testEmail = prompt('Digite o e-mail para teste:');
    if (!testEmail) {
        button.disabled = false;
        button.innerHTML = originalText;
        return;
    }
    
    fetch('<?= base_url('/settings/test-smtp') ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ email: testEmail })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('E-mail de teste enviado com sucesso!');
        } else {
            alert('Erro ao enviar e-mail: ' + data.message);
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
}
</script>

<?= $this->endSection() ?>