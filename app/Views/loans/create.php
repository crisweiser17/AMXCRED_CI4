<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex items-center mb-6">
        <a href="<?= base_url('/loans') ?>" class="text-gray-600 hover:text-gray-900 mr-4">
            <i class="fas fa-arrow-left text-xl"></i>
        </a>
        <div>
            <h1 class="text-3xl font-bold text-gray-900"><?= $title ?></h1>
            <p class="text-gray-600 mt-1">Preencha os dados para criar um novo empréstimo</p>
        </div>
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
        <form method="POST" action="<?= base_url('/loans/store') ?>" class="p-6">
            <?= csrf_field() ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Cliente -->
                <div class="md:col-span-2">
                    <label for="client_search" class="block text-sm font-medium text-gray-700 mb-2">
                        Cliente <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="text" id="client_search" placeholder="Digite o nome ou CPF do cliente..."
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?= isset($errors['client_id']) ? 'border-red-500' : '' ?>"
                               autocomplete="off">
                        <input type="hidden" id="client_id" name="client_id" value="<?= old('client_id') ?>" required>
                        
                        <!-- Lista de sugestões -->
                        <div id="client_suggestions" class="absolute z-10 w-full bg-white border border-gray-300 rounded-md shadow-lg mt-1 max-h-60 overflow-y-auto hidden">
                            <!-- Sugestões serão inseridas aqui via JavaScript -->
                        </div>
                        
                        <!-- Cliente selecionado -->
                        <div id="selected_client" class="mt-2 p-3 bg-blue-50 border border-blue-200 rounded-md hidden">
                            <div class="flex items-center justify-between">
                                <div>
                                    <div class="font-medium text-blue-900" id="selected_client_name"></div>
                                    <div class="text-sm text-blue-700" id="selected_client_info"></div>
                                </div>
                                <button type="button" id="clear_client" class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php if (isset($errors['client_id'])): ?>
                        <p class="text-red-500 text-sm mt-1"><?= $errors['client_id'] ?></p>
                    <?php endif; ?>
                </div>

                <!-- Plano de Empréstimo -->
                <div class="md:col-span-2">
                    <label for="loan_plan_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Plano de Empréstimo <span class="text-red-500">*</span>
                    </label>
                    <select id="loan_plan_id" name="loan_plan_id" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?= isset($errors['loan_plan_id']) ? 'border-red-500' : '' ?>">
                        <option value="">Selecione um plano</option>
                        <?php foreach ($loanPlans as $plan): ?>
                            <option value="<?= $plan['id'] ?>" 
                                    data-amount="<?= $plan['loan_amount'] ?>"
                                    data-total="<?= $plan['total_repayment_amount'] ?>"
                                    data-installments="<?= $plan['number_of_installments'] ?>"
                                    <?= old('loan_plan_id') == $plan['id'] ? 'selected' : '' ?>>
                                <?= esc($plan['name']) ?> - R$ <?= number_format($plan['loan_amount'], 2, ',', '.') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if (isset($errors['loan_plan_id'])): ?>
                        <p class="text-red-500 text-sm mt-1"><?= $errors['loan_plan_id'] ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Detalhes do Plano Selecionado -->
            <div id="planDetails" class="mt-6 p-4 bg-gray-50 rounded-lg hidden">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Detalhes do Plano Selecionado</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    <div class="bg-white p-3 rounded border">
                        <div class="text-sm text-gray-500">Valor do Empréstimo</div>
                        <div id="planAmount" class="text-lg font-semibold text-green-600">-</div>
                    </div>
                    <div class="bg-white p-3 rounded border">
                        <div class="text-sm text-gray-500">Valor Total a Pagar</div>
                        <div id="planTotal" class="text-lg font-semibold text-blue-600">-</div>
                    </div>
                    <div class="bg-white p-3 rounded border">
                        <div class="text-sm text-gray-500">Número de Parcelas</div>
                        <div id="planInstallments" class="text-lg font-semibold text-purple-600">-</div>
                    </div>
                    <div class="bg-white p-3 rounded border">
                        <div class="text-sm text-gray-500">Valor da Parcela</div>
                        <div id="planInstallmentAmount" class="text-lg font-semibold text-orange-600">-</div>
                    </div>
                    <div class="bg-white p-3 rounded border">
                        <div class="text-sm text-gray-500">Taxa de Juros Mensal</div>
                        <div id="planInterestRate" class="text-lg font-semibold text-red-600">-</div>
                    </div>
                </div>
            </div>

            <!-- Observações -->
            <div class="mt-6">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                    Observações (opcional)
                </label>
                <textarea id="notes" name="notes" rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                          placeholder="Observações sobre o empréstimo..."><?= old('notes') ?></textarea>
            </div>

            <!-- Botões -->
            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                <a href="<?= base_url('/loans') ?>" 
                   class="px-6 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 transition-colors">
                    Cancelar
                </a>
                <button type="submit" 
                        class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                    <i class="fas fa-save mr-2"></i>Criar Empréstimo
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const clientSearch = document.getElementById('client_search');
    const clientId = document.getElementById('client_id');
    const clientSuggestions = document.getElementById('client_suggestions');
    const selectedClient = document.getElementById('selected_client');
    const selectedClientName = document.getElementById('selected_client_name');
    const selectedClientInfo = document.getElementById('selected_client_info');
    const clearClient = document.getElementById('clear_client');
    
    const loanPlanSelect = document.getElementById('loan_plan_id');
    const planDetails = document.getElementById('planDetails');
    const planAmount = document.getElementById('planAmount');
    const planTotal = document.getElementById('planTotal');
    const planInstallments = document.getElementById('planInstallments');
    const planInstallmentAmount = document.getElementById('planInstallmentAmount');
    const planInterestRate = document.getElementById('planInterestRate');
    
    // Dados dos clientes (passados do PHP)
    const clients = <?= json_encode($clients) ?>;
    
    let searchTimeout;

    // Autocomplete para clientes
    clientSearch.addEventListener('input', function() {
        const query = this.value.trim();
        
        clearTimeout(searchTimeout);
        
        if (query.length < 2) {
            clientSuggestions.classList.add('hidden');
            return;
        }
        
        searchTimeout = setTimeout(() => {
            const filteredClients = clients.filter(client => 
                client.full_name.toLowerCase().includes(query.toLowerCase()) ||
                client.cpf.includes(query)
            );
            
            displayClientSuggestions(filteredClients);
        }, 300);
    });
    
    function displayClientSuggestions(filteredClients) {
        clientSuggestions.innerHTML = '';
        
        if (filteredClients.length === 0) {
            clientSuggestions.innerHTML = '<div class="p-3 text-gray-500">Nenhum cliente encontrado</div>';
        } else {
            filteredClients.forEach(client => {
                const suggestion = document.createElement('div');
                suggestion.className = 'p-3 hover:bg-gray-100 cursor-pointer border-b border-gray-200 last:border-b-0';
                suggestion.innerHTML = `
                    <div class="font-medium text-gray-900">${client.full_name}</div>
                    <div class="text-sm text-gray-600">CPF: ${client.cpf}</div>
                `;
                
                suggestion.addEventListener('click', () => selectClient(client));
                clientSuggestions.appendChild(suggestion);
            });
        }
        
        clientSuggestions.classList.remove('hidden');
    }
    
    function selectClient(client) {
        clientId.value = client.id;
        clientSearch.value = '';
        clientSuggestions.classList.add('hidden');
        
        selectedClientName.textContent = client.full_name;
        selectedClientInfo.textContent = `CPF: ${client.cpf}`;
        selectedClient.classList.remove('hidden');
    }
    
    clearClient.addEventListener('click', function() {
        clientId.value = '';
        clientSearch.value = '';
        selectedClient.classList.add('hidden');
        clientSuggestions.classList.add('hidden');
    });
    
    // Esconder sugestões ao clicar fora
    document.addEventListener('click', function(e) {
        if (!clientSearch.contains(e.target) && !clientSuggestions.contains(e.target)) {
            clientSuggestions.classList.add('hidden');
        }
    });
    
    // Carregar cliente selecionado se houver (para casos de erro de validação ou pré-seleção)
         const preSelectedClientId = clientId.value || '<?= $preSelectedClientId ?? '' ?>';
         if (preSelectedClientId) {
             const preSelectedClient = clients.find(client => client.id == preSelectedClientId);
             if (preSelectedClient) {
                 selectClient(preSelectedClient);
             }
         }
    
    function formatCurrency(value) {
        return 'R$ ' + parseFloat(value).toLocaleString('pt-BR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }
    
    function calculateMonthlyInterestRate(loanAmount, totalAmount, installments) {
        if (loanAmount <= 0 || installments <= 0 || totalAmount <= loanAmount) {
            return 0;
        }
        
        const rate = Math.pow((totalAmount / loanAmount), (1 / installments)) - 1;
        return rate * 100;
    }

    function updatePlanDetails() {
        const selectedOption = loanPlanSelect.options[loanPlanSelect.selectedIndex];
        
        if (selectedOption.value) {
            const amount = parseFloat(selectedOption.dataset.amount);
            const total = parseFloat(selectedOption.dataset.total);
            const installments = parseInt(selectedOption.dataset.installments);
            const interestRate = calculateMonthlyInterestRate(amount, total, installments);
            
            planAmount.textContent = formatCurrency(amount);
            planTotal.textContent = formatCurrency(total);
            planInstallments.textContent = installments + 'x';
            planInstallmentAmount.textContent = formatCurrency(total / installments);
            planInterestRate.textContent = interestRate.toFixed(2) + '% ao mês';
            
            planDetails.classList.remove('hidden');
        } else {
            planDetails.classList.add('hidden');
        }
    }

    loanPlanSelect.addEventListener('change', updatePlanDetails);
    
    // Atualizar detalhes se já há um plano selecionado (caso de erro de validação)
    if (loanPlanSelect.value) {
        updatePlanDetails();
    }
});
</script>
<?= $this->endSection() ?>