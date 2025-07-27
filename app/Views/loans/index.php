<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900"><?= $title ?></h1>
            <p class="text-gray-600 mt-1">Gerencie todos os empréstimos do sistema</p>
        </div>
        <a href="<?= base_url('/loans/create') ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
            <i class="fas fa-plus mr-2"></i>Novo Empréstimo
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

    <?php if (isset($error)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?= $error ?>
        </div>
    <?php endif; ?>

    <!-- Filtros -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
        <form method="GET" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-48">
                <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Buscar</label>
                <input type="text" id="search" name="search" value="<?= esc($search ?? '') ?>" 
                       placeholder="Nome do cliente, CPF ou ID do empréstimo"
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select id="status" name="status" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos</option>
                    <option value="pending_acceptance" <?= ($status ?? '') === 'pending_acceptance' ? 'selected' : '' ?>>Aguardando Aceitação</option>
                    <option value="accepted" <?= ($status ?? '') === 'accepted' ? 'selected' : '' ?>>Aceito</option>
                    <option value="pending_funding" <?= ($status ?? '') === 'pending_funding' ? 'selected' : '' ?>>Aguardando Financiamento</option>
                    <option value="funded" <?= ($status ?? '') === 'funded' ? 'selected' : '' ?>>Financiado</option>
                    <option value="active" <?= ($status ?? '') === 'active' ? 'selected' : '' ?>>Ativo</option>
                    <option value="completed" <?= ($status ?? '') === 'completed' ? 'selected' : '' ?>>Concluído</option>
                    <option value="cancelled" <?= ($status ?? '') === 'cancelled' ? 'selected' : '' ?>>Cancelado</option>
                    <option value="defaulted" <?= ($status ?? '') === 'defaulted' ? 'selected' : '' ?>>Inadimplente</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition-colors">
                    <i class="fas fa-search mr-1"></i>Filtrar
                </button>
                <a href="<?= base_url('/loans') ?>" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md transition-colors">
                    <i class="fas fa-times mr-1"></i>Limpar
                </a>
            </div>
        </form>
    </div>

    <!-- Tabela de Empréstimos -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
        <?php if (empty($loans)): ?>
            <div class="text-center py-12">
                <i class="fas fa-hand-holding-usd text-gray-400 text-6xl mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Nenhum empréstimo encontrado</h3>
                <p class="text-gray-500 mb-4">Comece criando o primeiro empréstimo do sistema.</p>
                <a href="<?= base_url('/loans/create') ?>" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    <i class="fas fa-plus mr-2"></i>Criar Primeiro Empréstimo
                </a>
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plano</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data Criação</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($loans as $loan): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    #<?= $loan['id'] ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900"><?= esc($loan['client_name']) ?></div>
                                    <div class="text-sm text-gray-500"><?= esc($loan['client_cpf']) ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900"><?= esc($loan['plan_name']) ?></div>
                                    <div class="text-sm text-gray-500"><?= $loan['number_of_installments'] ?>x parcelas</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900"><?= $loan['loan_amount_formatted'] ?></div>
                                    <div class="text-sm text-gray-500">Total: <?= $loan['total_repayment_amount_formatted'] ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full <?= $loan['status_class'] ?>">
                                        <?= $loan['status_label'] ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= $loan['created_at_formatted'] ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="<?= base_url('/loans/view/' . $loan['id']) ?>" 
                                           class="text-blue-600 hover:text-blue-900 transition-colors" 
                                           title="Visualizar">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        <?php if ($loan['status'] === 'pending_acceptance' && !empty($loan['acceptance_token'])): ?>
                                            <button onclick="copyAcceptanceLink('<?= $loan['acceptance_token'] ?>')" 
                                                    class="text-purple-600 hover:text-purple-900 transition-colors" 
                                                    title="Copiar Link de Aceitação">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                            <button onclick="sendNotification(<?= $loan['id'] ?>, '<?= $loan['acceptance_token'] ?>')" 
                                                    class="text-orange-600 hover:text-orange-900 transition-colors" 
                                                    title="Enviar Notificação">
                                                <i class="fas fa-paper-plane"></i>
                                            </button>
                                        <?php endif; ?>
                                        
                                        <?php if ($loan['status'] === 'accepted'): ?>
                                            <button onclick="showFundModal(<?= $loan['id'] ?>)" 
                                                    class="text-green-600 hover:text-green-900 transition-colors" 
                                                    title="Financiar">
                                                <i class="fas fa-dollar-sign"></i>
                                            </button>
                                        <?php endif; ?>
                                        
                                        <?php if (in_array($loan['status'], ['pending_acceptance', 'accepted', 'pending_funding'])): ?>
                                            <button onclick="confirmCancel(<?= $loan['id'] ?>)" 
                                                    class="text-red-600 hover:text-red-900 transition-colors" 
                                                    title="Cancelar">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal de Financiamento -->
<div id="fundModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Financiar Empréstimo</h3>
                <form id="fundForm" method="POST">
                    <div class="mb-4">
                        <label for="pix_transaction_id" class="block text-sm font-medium text-gray-700 mb-2">
                            ID da Transação PIX (opcional)
                        </label>
                        <input type="text" id="pix_transaction_id" name="pix_transaction_id" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Ex: E12345678901234567890123456789012345">
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeFundModal()" 
                                class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 transition-colors">
                            Cancelar
                        </button>
                        <button type="submit" 
                                class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors">
                            Confirmar Financiamento
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function showFundModal(loanId) {
    document.getElementById('fundForm').action = '<?= base_url('/loans/fund/') ?>' + loanId;
    document.getElementById('fundModal').classList.remove('hidden');
}

function closeFundModal() {
    document.getElementById('fundModal').classList.add('hidden');
    document.getElementById('pix_transaction_id').value = '';
}

function confirmCancel(loanId) {
    if (confirm('Tem certeza que deseja cancelar este empréstimo?')) {
        window.location.href = '<?= base_url('/loans/cancel/') ?>' + loanId;
    }
}

// Função para copiar link de aceitação
function copyAcceptanceLink(token) {
    const acceptanceUrl = '<?= base_url() ?>/accept-loan/' + token;
    
    if (navigator.clipboard && window.isSecureContext) {
        navigator.clipboard.writeText(acceptanceUrl).then(function() {
            showToast('Link copiado para a área de transferência!', 'success');
        }).catch(function(err) {
            console.error('Erro ao copiar: ', err);
            fallbackCopyTextToClipboard(acceptanceUrl);
        });
    } else {
        fallbackCopyTextToClipboard(acceptanceUrl);
    }
}

// Fallback para navegadores que não suportam clipboard API
function fallbackCopyTextToClipboard(text) {
    const textArea = document.createElement('textarea');
    textArea.value = text;
    textArea.style.top = '0';
    textArea.style.left = '0';
    textArea.style.position = 'fixed';
    
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    
    try {
        const successful = document.execCommand('copy');
        if (successful) {
            showToast('Link copiado para a área de transferência!', 'success');
        } else {
            showToast('Erro ao copiar o link', 'error');
        }
    } catch (err) {
        console.error('Fallback: Erro ao copiar', err);
        showToast('Erro ao copiar o link', 'error');
    }
    
    document.body.removeChild(textArea);
}

// Função para enviar notificação
function sendNotification(loanId, token) {
    if (confirm('Deseja enviar o link de aceitação para o cliente?')) {
        // Mostrar loading
        showToast('Enviando notificação...', 'info');
        
        // Fazer requisição para enviar notificação
        fetch('<?= base_url('/loans/send-notification/') ?>' + loanId, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                token: token
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message || 'Notificação enviada com sucesso!', 'success');
            } else {
                showToast(data.message || 'Erro ao enviar notificação', 'error');
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            showToast('Erro ao enviar notificação', 'error');
        });
    }
}

// Função para mostrar toast notifications
function showToast(message, type = 'info') {
    // Remove toast anterior se existir
    const existingToast = document.getElementById('toast');
    if (existingToast) {
        existingToast.remove();
    }
    
    const toast = document.createElement('div');
    toast.id = 'toast';
    toast.className = `fixed top-4 right-4 z-50 px-4 py-3 rounded-lg shadow-lg text-white transition-all duration-300 transform translate-x-full`;
    
    // Definir cores baseadas no tipo
    switch(type) {
        case 'success':
            toast.className += ' bg-green-500';
            break;
        case 'error':
            toast.className += ' bg-red-500';
            break;
        case 'info':
        default:
            toast.className += ' bg-blue-500';
            break;
    }
    
    toast.innerHTML = `
        <div class="flex items-center">
            <span>${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-3 text-white hover:text-gray-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    // Animar entrada
    setTimeout(() => {
        toast.classList.remove('translate-x-full');
    }, 100);
    
    // Auto remover após 5 segundos
    setTimeout(() => {
        if (toast.parentElement) {
            toast.classList.add('translate-x-full');
            setTimeout(() => {
                if (toast.parentElement) {
                    toast.remove();
                }
            }, 300);
        }
    }, 5000);
}

// Fechar modal ao clicar fora
document.getElementById('fundModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeFundModal();
    }
});
</script>
<?= $this->endSection() ?>