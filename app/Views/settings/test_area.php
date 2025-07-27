<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 mb-2"><?= $title ?></h1>
        <p class="text-gray-600">Área para testes de funcionalidades do sistema de empréstimos</p>
    </div>

    <!-- Alertas -->
    <div id="alert-container" class="mb-4"></div>

    <!-- Lista de Empréstimos -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Empréstimos Cadastrados</h2>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plano</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="loans-table-body">
                    <?php if (empty($loans)): ?>
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                Nenhum empréstimo encontrado
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($loans as $loan): ?>
                            <tr id="loan-row-<?= $loan['id'] ?>">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    #<?= $loan['id'] ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900"><?= esc($loan['client_name']) ?></div>
                                    <div class="text-sm text-gray-500"><?= esc($loan['client_cpf']) ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?= esc($loan['plan_name']) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    R$ <?= number_format($loan['amount'], 2, ',', '.') ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span id="status-badge-<?= $loan['id'] ?>" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full <?= $loan['status_class'] ?>">
                                        <?= $loan['status_label'] ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= $loan['created_at_formatted'] ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <!-- Botão Alterar Status -->
                                        <button onclick="openStatusModal(<?= $loan['id'] ?>, '<?= $loan['status'] ?>')" 
                                                class="text-blue-600 hover:text-blue-900 bg-blue-100 hover:bg-blue-200 px-2 py-1 rounded text-xs">
                                            Status
                                        </button>
                                        
                                        <!-- Botão Ver Parcelas -->
                                        <button onclick="openInstallmentsModal(<?= $loan['id'] ?>)" 
                                                class="text-green-600 hover:text-green-900 bg-green-100 hover:bg-green-200 px-2 py-1 rounded text-xs">
                                            Parcelas
                                        </button>
                                        
                                        <!-- Botão Deletar -->
                                        <button onclick="deleteLoan(<?= $loan['id'] ?>)" 
                                                class="text-red-600 hover:text-red-900 bg-red-100 hover:bg-red-200 px-2 py-1 rounded text-xs">
                                            Deletar
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal para Alterar Status -->
<div id="status-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Alterar Status do Empréstimo</h3>
            
            <form id="status-form">
                <input type="hidden" id="status-loan-id" name="loan_id">
                
                <div class="mb-4">
                    <label for="status-select" class="block text-sm font-medium text-gray-700 mb-2">Novo Status:</label>
                    <select id="status-select" name="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="pending_acceptance">Aguardando Aceitação</option>
                        <option value="accepted">Aceito</option>
                        <option value="pending_funding">Aguardando Financiamento</option>
                        <option value="funded">Financiado</option>
                        <option value="active">Ativo</option>
                        <option value="completed">Concluído</option>
                        <option value="cancelled">Cancelado</option>
                        <option value="defaulted">Inadimplente</option>
                    </select>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeStatusModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Cancelar
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Atualizar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para Ver/Gerenciar Parcelas -->
<div id="installments-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-10 mx-auto p-5 border w-4/5 max-w-4xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Parcelas do Empréstimo #<span id="installments-loan-id"></span></h3>
                <button onclick="closeInstallmentsModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div id="installments-loading" class="text-center py-4 hidden">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                <p class="mt-2 text-gray-600">Carregando parcelas...</p>
            </div>
            
            <div id="installments-content" class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Parcela</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vencimento</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valor</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pago em</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                        </tr>
                    </thead>
                    <tbody id="installments-table-body" class="bg-white divide-y divide-gray-200">
                        <!-- Conteúdo será carregado via JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Marcar Parcela como Paga -->
<div id="payment-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Marcar Parcela como Paga</h3>
            
            <form id="payment-form">
                <input type="hidden" id="payment-installment-id" name="installment_id">
                
                <div class="mb-4">
                    <label for="paid-date" class="block text-sm font-medium text-gray-700 mb-2">Data do Pagamento:</label>
                    <input type="date" id="paid-date" name="paid_date" required 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="mb-4">
                    <label for="paid-amount" class="block text-sm font-medium text-gray-700 mb-2">Valor Pago (R$):</label>
                    <input type="text" id="paid-amount" name="paid_amount" required placeholder="0,00"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closePaymentModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Cancelar
                    </button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                        Marcar como Pago
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Funções para gerenciar modais
function openStatusModal(loanId, currentStatus) {
    document.getElementById('status-loan-id').value = loanId;
    document.getElementById('status-select').value = currentStatus;
    document.getElementById('status-modal').classList.remove('hidden');
}

function closeStatusModal() {
    document.getElementById('status-modal').classList.add('hidden');
}

function openInstallmentsModal(loanId) {
    document.getElementById('installments-loan-id').textContent = loanId;
    document.getElementById('installments-modal').classList.remove('hidden');
    loadInstallments(loanId);
}

function closeInstallmentsModal() {
    document.getElementById('installments-modal').classList.add('hidden');
}

function openPaymentModal(installmentId, currentAmount) {
    document.getElementById('payment-installment-id').value = installmentId;
    document.getElementById('paid-date').value = new Date().toISOString().split('T')[0];
    document.getElementById('paid-amount').value = currentAmount;
    document.getElementById('payment-modal').classList.remove('hidden');
}

function closePaymentModal() {
    document.getElementById('payment-modal').classList.add('hidden');
}

// Função para mostrar alertas
function showAlert(message, type = 'success') {
    const alertContainer = document.getElementById('alert-container');
    const alertClass = type === 'success' ? 'bg-green-100 border-green-500 text-green-700' : 'bg-red-100 border-red-500 text-red-700';
    
    alertContainer.innerHTML = `
        <div class="border-l-4 ${alertClass} p-4 mb-4">
            <div class="flex">
                <div class="ml-3">
                    <p class="text-sm">${message}</p>
                </div>
            </div>
        </div>
    `;
    
    // Remover alerta após 5 segundos
    setTimeout(() => {
        alertContainer.innerHTML = '';
    }, 5000);
}

// Atualizar status do empréstimo
document.getElementById('status-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('<?= base_url('settings/test-area/update-loan-status') ?>', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const loanId = formData.get('loan_id');
            const statusBadge = document.getElementById(`status-badge-${loanId}`);
            statusBadge.textContent = data.new_status_label;
            statusBadge.className = `inline-flex px-2 py-1 text-xs font-semibold rounded-full ${data.new_status_class}`;
            
            showAlert(data.message, 'success');
            closeStatusModal();
        } else {
            showAlert(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        showAlert('Erro ao atualizar status', 'error');
    });
});

// Deletar empréstimo
function deleteLoan(loanId) {
    if (!confirm('Tem certeza que deseja deletar este empréstimo? Esta ação não pode ser desfeita.')) {
        return;
    }
    
    const formData = new FormData();
    formData.append('loan_id', loanId);
    
    fetch('<?= base_url('settings/test-area/delete-loan') ?>', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById(`loan-row-${loanId}`).remove();
            showAlert(data.message, 'success');
        } else {
            showAlert(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        showAlert('Erro ao deletar empréstimo', 'error');
    });
}

// Carregar parcelas
function loadInstallments(loanId) {
    document.getElementById('installments-loading').classList.remove('hidden');
    document.getElementById('installments-content').classList.add('hidden');
    
    fetch(`<?= base_url('settings/test-area/get-loan-installments') ?>/${loanId}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById('installments-loading').classList.add('hidden');
        document.getElementById('installments-content').classList.remove('hidden');
        
        if (data.success) {
            const tbody = document.getElementById('installments-table-body');
            tbody.innerHTML = '';
            
            if (data.installments.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="px-4 py-4 text-center text-gray-500">Nenhuma parcela encontrada</td></tr>';
            } else {
                data.installments.forEach(installment => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">${installment.installment_number}</td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">${installment.due_date_formatted}</td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">${installment.amount_formatted}</td>
                        <td class="px-4 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full ${installment.status_class}">
                                ${installment.status_label}
                            </span>
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                            ${installment.paid_at_formatted || '-'}
                        </td>
                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium">
                            ${installment.status !== 'paid' ? 
                                `<button onclick="openPaymentModal(${installment.id}, '${installment.amount}')" 
                                         class="text-green-600 hover:text-green-900 bg-green-100 hover:bg-green-200 px-2 py-1 rounded text-xs">
                                    Marcar Pago
                                </button>` : 
                                '<span class="text-gray-400 text-xs">Pago</span>'
                            }
                        </td>
                    `;
                    tbody.appendChild(row);
                });
            }
        } else {
            showAlert(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        document.getElementById('installments-loading').classList.add('hidden');
        document.getElementById('installments-content').classList.remove('hidden');
        showAlert('Erro ao carregar parcelas', 'error');
    });
}

// Marcar parcela como paga
document.getElementById('payment-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('<?= base_url('settings/test-area/mark-installment-paid') ?>', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert(data.message, 'success');
            closePaymentModal();
            // Recarregar parcelas
            const loanId = document.getElementById('installments-loan-id').textContent;
            loadInstallments(loanId);
        } else {
            showAlert(data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        showAlert('Erro ao marcar parcela como paga', 'error');
    });
});

// Formatação de valor monetário
document.getElementById('paid-amount').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    value = (value / 100).toFixed(2) + '';
    value = value.replace('.', ',');
    value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    e.target.value = value;
});
</script>

<?= $this->endSection() ?>