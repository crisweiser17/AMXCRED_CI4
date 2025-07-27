<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('content') ?>
<div class="max-w-4xl mx-auto">
    <!-- Page Header -->
    <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900"><?= $title ?></h1>
                <p class="mt-1 text-sm text-gray-600">
                    <?= isset($plan) ? 'Edite as informações do plano de empréstimo' : 'Preencha os dados para criar um novo plano de empréstimo' ?>
                </p>
            </div>
            <a href="<?= base_url('/settings/loan-plans') ?>" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Voltar
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white shadow-sm rounded-lg">
        <form action="<?= isset($plan) ? base_url('/settings/loan-plans/update/' . $plan['id']) : base_url('/settings/loan-plans/store') ?>" method="POST" id="loanPlanForm">
            <div class="p-6 space-y-6">
                
                <!-- Nome do Plano -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">
                        Nome do Plano <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1">
                        <input type="text" 
                               name="name" 
                               id="name" 
                               value="<?= old('name', $plan['name'] ?? '') ?>"
                               class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md <?= isset($errors['name']) ? 'border-red-300' : '' ?>"
                               placeholder="Ex: Plano Bronze, Empréstimo Rápido 500"
                               required>
                    </div>
                    <?php if (isset($errors['name'])): ?>
                        <p class="mt-2 text-sm text-red-600"><?= $errors['name'] ?></p>
                    <?php endif; ?>
                    <p class="mt-2 text-sm text-gray-500">Nome comercial que será exibido para os operadores</p>
                </div>

                <!-- Valores -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Valor do Empréstimo -->
                    <div>
                        <label for="loan_amount" class="block text-sm font-medium text-gray-700">
                            Valor do Empréstimo <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">R$</span>
                            </div>
                            <input type="number" 
                                   name="loan_amount" 
                                   id="loan_amount" 
                                   step="0.01"
                                   min="0.01"
                                   value="<?= old('loan_amount', $plan['loan_amount'] ?? '') ?>"
                                   class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md <?= isset($errors['loan_amount']) ? 'border-red-300' : '' ?>"
                                   placeholder="0,00"
                                   required
                                   onchange="calculateInterest()">
                        </div>
                        <?php if (isset($errors['loan_amount'])): ?>
                            <p class="mt-2 text-sm text-red-600"><?= $errors['loan_amount'] ?></p>
                        <?php endif; ?>
                        <p class="mt-2 text-sm text-gray-500">Valor que será depositado na conta do cliente</p>
                    </div>

                    <!-- Total a Pagar -->
                    <div>
                        <label for="total_repayment_amount" class="block text-sm font-medium text-gray-700">
                            Total a Pagar <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">R$</span>
                            </div>
                            <input type="number" 
                                   name="total_repayment_amount" 
                                   id="total_repayment_amount" 
                                   step="0.01"
                                   min="0.01"
                                   value="<?= old('total_repayment_amount', $plan['total_repayment_amount'] ?? '') ?>"
                                   class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 sm:text-sm border-gray-300 rounded-md <?= isset($errors['total_repayment_amount']) ? 'border-red-300' : '' ?>"
                                   placeholder="0,00"
                                   required
                                   onchange="calculateInterest()">
                        </div>
                        <?php if (isset($errors['total_repayment_amount'])): ?>
                            <p class="mt-2 text-sm text-red-600"><?= $errors['total_repayment_amount'] ?></p>
                        <?php endif; ?>
                        <p class="mt-2 text-sm text-gray-500">Soma total que o cliente pagará (principal + juros)</p>
                    </div>
                </div>

                <!-- Número de Parcelas -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="number_of_installments" class="block text-sm font-medium text-gray-700">
                            Número de Parcelas <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1">
                            <input type="number" 
                                   name="number_of_installments" 
                                   id="number_of_installments" 
                                   min="1"
                                   value="<?= old('number_of_installments', $plan['number_of_installments'] ?? '') ?>"
                                   class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md <?= isset($errors['number_of_installments']) ? 'border-red-300' : '' ?>"
                                   placeholder="6"
                                   required
                                   onchange="calculateInterest()">
                        </div>
                        <?php if (isset($errors['number_of_installments'])): ?>
                            <p class="mt-2 text-sm text-red-600"><?= $errors['number_of_installments'] ?></p>
                        <?php endif; ?>
                        <p class="mt-2 text-sm text-gray-500">Quantidade de parcelas mensais</p>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="is_active" class="block text-sm font-medium text-gray-700">
                            Status do Plano
                        </label>
                        <div class="mt-1">
                            <select name="is_active" 
                                    id="is_active" 
                                    class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                <option value="1" <?= old('is_active', $plan['is_active'] ?? '1') == '1' ? 'selected' : '' ?>>Ativo</option>
                                <option value="0" <?= old('is_active', $plan['is_active'] ?? '1') == '0' ? 'selected' : '' ?>>Inativo</option>
                            </select>
                        </div>
                        <p class="mt-2 text-sm text-gray-500">Planos inativos não aparecem para seleção</p>
                    </div>
                </div>

                <!-- Cálculos Automáticos -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Cálculos Automáticos</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-white p-3 rounded-md border">
                            <div class="text-sm font-medium text-gray-500">Valor da Parcela</div>
                            <div class="text-lg font-semibold text-gray-900" id="installment_display">R$ 0,00</div>
                        </div>
                        <div class="bg-white p-3 rounded-md border">
                            <div class="text-sm font-medium text-gray-500">Total de Juros</div>
                            <div class="text-lg font-semibold text-gray-900" id="total_interest_display">R$ 0,00</div>
                        </div>
                        <div class="bg-white p-3 rounded-md border">
                            <div class="text-sm font-medium text-gray-500">Juros Mensal</div>
                            <div class="text-lg font-semibold text-gray-900" id="monthly_rate_display">0,00%</div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Form Actions -->
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-end space-x-3">
                <a href="<?= base_url('/settings/loan-plans') ?>" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Cancelar
                </a>
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <?= isset($plan) ? 'Atualizar Plano' : 'Criar Plano' ?>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function calculateInterest() {
    const loanAmount = parseFloat(document.getElementById('loan_amount').value) || 0;
    const totalAmount = parseFloat(document.getElementById('total_repayment_amount').value) || 0;
    const installments = parseInt(document.getElementById('number_of_installments').value) || 1;
    
    // Valor da parcela
    const installmentAmount = totalAmount / installments;
    document.getElementById('installment_display').textContent = 'R$ ' + installmentAmount.toLocaleString('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
    
    // Total de juros
    const totalInterest = totalAmount - loanAmount;
    document.getElementById('total_interest_display').textContent = 'R$ ' + totalInterest.toLocaleString('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
    
    // Juros mensal
    let monthlyRate = 0;
    if (loanAmount > 0 && installments > 0 && totalAmount > loanAmount) {
        monthlyRate = (Math.pow(totalAmount / loanAmount, 1 / installments) - 1) * 100;
    }
    document.getElementById('monthly_rate_display').textContent = monthlyRate.toLocaleString('pt-BR', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }) + '%';
}

// Calcular ao carregar a página se há valores
document.addEventListener('DOMContentLoaded', function() {
    calculateInterest();
});

// Validação do formulário
document.getElementById('loanPlanForm').addEventListener('submit', function(e) {
    const loanAmount = parseFloat(document.getElementById('loan_amount').value) || 0;
    const totalAmount = parseFloat(document.getElementById('total_repayment_amount').value) || 0;
    
    if (totalAmount <= loanAmount) {
        e.preventDefault();
        alert('O valor total a pagar deve ser maior que o valor do empréstimo.');
        return false;
    }
});
</script>
<?= $this->endSection() ?>