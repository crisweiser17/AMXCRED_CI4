<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('content') ?>
<!-- Dashboard Content -->
<div class="p-6">
    <?php if (session()->getFlashdata('success')): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <i class="fas fa-check-circle mr-2"></i>
            <?= session()->getFlashdata('success') ?>
        </div>
    <?php endif; ?>
    
    <?php if (session()->getFlashdata('error')): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            <?= session()->getFlashdata('error') ?>
        </div>
    <?php endif; ?>
    
    <!-- Welcome Message -->
    <div class="mb-6">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h4 class="text-xl font-semibold text-gray-800 mb-2">
                <i class="fas fa-hand-wave mr-2 text-yellow-500"></i>
                Bem-vindo, <?= esc($user['name']) ?>!
            </h4>
            <p class="text-gray-600">
                Você está logado como <strong><?= ucfirst($user['role']) ?></strong>. 
                Último acesso: <?= $user['last_login_at'] ? date('d/m/Y H:i', strtotime($user['last_login_at'])) : 'Primeiro acesso' ?>
            </p>
        </div>
    </div>
    
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg shadow-md p-6 text-white text-center">
            <i class="fas fa-users text-3xl mb-3"></i>
            <h3 class="text-2xl font-bold mb-1">0</h3>
            <p class="text-blue-100">Clientes</p>
        </div>
        
        <div class="bg-gradient-to-r from-green-500 to-green-400 rounded-lg shadow-md p-6 text-white text-center">
            <i class="fas fa-money-bill-wave text-3xl mb-3"></i>
            <h3 class="text-2xl font-bold mb-1">0</h3>
            <p class="text-green-100">Empréstimos Ativos</p>
        </div>
        
        <div class="bg-gradient-to-r from-red-500 to-pink-500 rounded-lg shadow-md p-6 text-white text-center">
            <i class="fas fa-exclamation-triangle text-3xl mb-3"></i>
            <h3 class="text-2xl font-bold mb-1">0</h3>
            <p class="text-red-100">Pendências</p>
        </div>
        
        <div class="bg-gradient-to-r from-cyan-500 to-blue-500 rounded-lg shadow-md p-6 text-white text-center">
            <i class="fas fa-chart-line text-3xl mb-3"></i>
            <h3 class="text-2xl font-bold mb-1">R$ 0,00</h3>
            <p class="text-cyan-100">Total Emprestado</p>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-lg shadow-md">
            <div class="px-6 py-4 border-b border-gray-200">
                <h5 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-bolt mr-2"></i>
                    Ações Rápidas
                </h5>
            </div>
            <div class="p-6">
                <div class="space-y-3">
                    <a href="<?= base_url('clients/create') ?>" class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200">
                        <i class="fas fa-user-plus mr-2"></i>
                        Novo Cliente
                    </a>
                    <a href="<?= base_url('loans/create') ?>" class="block w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200">
                        <i class="fas fa-plus mr-2"></i>
                        Novo Empréstimo
                    </a>
                    <a href="<?= base_url('loan_plans/create') ?>" class="block w-full bg-cyan-600 hover:bg-cyan-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200">
                        <i class="fas fa-list-alt mr-2"></i>
                        Novo Plano
                    </a>
                    <div class="relative">
                        <a href="<?= base_url('register') ?>" target="_blank" class="block w-full bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-4 rounded-lg transition duration-200">
                            <i class="fas fa-external-link-alt mr-2"></i>
                            Página Pública de Cadastro
                        </a>
                        <button onclick="copyToClipboard('<?= base_url('register') ?>')" class="absolute right-2 top-1/2 transform -translate-y-1/2 text-purple-200 hover:text-white transition duration-200" title="Copiar link">
                            <i class="fas fa-copy"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md">
            <div class="px-6 py-4 border-b border-gray-200">
                <h5 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-info-circle mr-2"></i>
                    Informações do Sistema
                </h5>
            </div>
            <div class="p-6">
                <p class="mb-2"><strong>Versão:</strong> 1.0.0</p>
                <p class="mb-2"><strong>Framework:</strong> CodeIgniter 4</p>
                <p class="mb-2"><strong>Status:</strong> <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-sm">Online</span></p>
                <p class="mb-0"><strong>Última atualização:</strong> <?= date('d/m/Y') ?></p>
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Criar notificação de sucesso
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
        notification.innerHTML = '<i class="fas fa-check mr-2"></i>Link copiado para a área de transferência!';
        document.body.appendChild(notification);
        
        // Remover notificação após 3 segundos
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }).catch(function(err) {
        console.error('Erro ao copiar: ', err);
        // Fallback para navegadores mais antigos
        const textArea = document.createElement('textarea');
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        
        // Criar notificação de sucesso
        const notification = document.createElement('div');
        notification.className = 'fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50';
        notification.innerHTML = '<i class="fas fa-check mr-2"></i>Link copiado para a área de transferência!';
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 3000);
    });
}
</script>

<?= $this->endSection() ?>