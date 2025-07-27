<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('content') ?>
<div class="container mx-auto px-4 py-6">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center">
            <a href="<?= base_url('/loans') ?>" class="text-gray-600 hover:text-gray-900 mr-4">
                <i class="fas fa-arrow-left text-xl"></i>
            </a>
            <div>
                <h1 class="text-3xl font-bold text-gray-900"><?= $title ?></h1>
                <p class="text-gray-600 mt-1">Informações completas do empréstimo</p>
            </div>
        </div>
        <div class="flex space-x-3">
            <?php if ($loan['status'] === 'accepted'): ?>
                <button onclick="showFundModal()" 
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    <i class="fas fa-dollar-sign mr-2"></i>Financiar
                </button>
            <?php endif; ?>
            
            <?php if (in_array($loan['status'], ['pending_acceptance', 'accepted', 'pending_funding'])): ?>
                <button onclick="confirmCancel()" 
                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    <i class="fas fa-times mr-2"></i>Cancelar
                </button>
            <?php endif; ?>
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

    <!-- Indicador de Progresso -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-6">Progresso do Empréstimo</h2>
        
        <?php 
        $steps = [
            'pending_acceptance' => ['step' => 1, 'label' => 'Aguardando Aceitação', 'icon' => 'fas fa-clock'],
            'accepted' => ['step' => 2, 'label' => 'Aceito pelo Cliente', 'icon' => 'fas fa-check'],
            'pending_funding' => ['step' => 3, 'label' => 'Aguardando Financiamento', 'icon' => 'fas fa-hourglass-half'],
            'funded' => ['step' => 3, 'label' => 'Financiado', 'icon' => 'fas fa-dollar-sign'],
            'active' => ['step' => 4, 'label' => 'Ativo', 'icon' => 'fas fa-play'],
            'completed' => ['step' => 5, 'label' => 'Concluído', 'icon' => 'fas fa-flag-checkered'],
            'cancelled' => ['step' => 0, 'label' => 'Cancelado', 'icon' => 'fas fa-times'],
            'defaulted' => ['step' => 0, 'label' => 'Inadimplente', 'icon' => 'fas fa-exclamation-triangle']
        ];
        
        $currentStep = $steps[$loan['status']]['step'] ?? 1;
        $maxSteps = 5;
        ?>
        
        <div class="flex items-center justify-between mb-4">
            <?php for ($i = 1; $i <= $maxSteps; $i++): ?>
                <div class="flex flex-col items-center">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold mb-2 <?= $i <= $currentStep ? 'bg-green-500' : 'bg-gray-300' ?>">
                        <?= $i ?>
                    </div>
                    <div class="text-xs text-center max-w-20">
                        <?php 
                        $stepLabels = ['', 'Criação', 'Aceitação', 'Financiamento', 'Ativo', 'Concluído'];
                        echo $stepLabels[$i];
                        ?>
                    </div>
                </div>
                <?php if ($i < $maxSteps): ?>
                    <div class="flex-1 h-1 mx-2 <?= $i < $currentStep ? 'bg-green-500' : 'bg-gray-300' ?>"></div>
                <?php endif; ?>
            <?php endfor; ?>
        </div>
        
        <div class="text-center">
            <span class="inline-flex items-center px-3 py-1 text-sm font-semibold rounded-full <?= $loan['status_class'] ?>">
                <i class="<?= $steps[$loan['status']]['icon'] ?> mr-2"></i>
                <?= $loan['status_label'] ?>
            </span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Informações Principais -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Status e Resumo -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-semibold text-gray-900">Status do Empréstimo</h2>
                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full <?= $loan['status_class'] ?>">
                        <?= $loan['status_label'] ?>
                    </span>
                </div>
                
                <!-- Informações do Plano -->
                <div class="mb-4 p-4 bg-gray-50 rounded-lg">
                    <div class="text-center">
                        <label class="block text-sm font-medium text-gray-500">Plano</label>
                        <div class="text-lg font-semibold text-gray-900"><?= esc($loan['plan_name']) ?></div>
                    </div>
                </div>
                
                <!-- Primeira linha: Valor do Empréstimo e Total a Pagar -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div class="text-center p-4 bg-green-50 rounded-lg">
                        <div class="text-2xl font-bold text-green-600"><?= $loan['loan_amount_formatted'] ?></div>
                        <div class="text-sm text-gray-600">Valor do Empréstimo</div>
                    </div>
                    <div class="text-center p-4 bg-blue-50 rounded-lg">
                        <div class="text-2xl font-bold text-blue-600"><?= $loan['total_repayment_amount_formatted'] ?></div>
                        <div class="text-sm text-gray-600">Total a Pagar</div>
                    </div>
                </div>
                
                <!-- Segunda linha: Parcelas, Valor da Parcela e Taxa de Juros -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="text-center p-4 bg-purple-50 rounded-lg">
                        <div class="text-2xl font-bold text-purple-600"><?= $loan['number_of_installments'] ?>x</div>
                        <div class="text-sm text-gray-600">Parcelas</div>
                    </div>
                    <?php if (isset($loan['installment_amount_formatted'])): ?>
                        <div class="text-center p-4 bg-orange-50 rounded-lg">
                            <div class="text-2xl font-bold text-orange-600"><?= $loan['installment_amount_formatted'] ?></div>
                            <div class="text-sm text-gray-600">Valor da Parcela</div>
                        </div>
                    <?php endif; ?>
                    <div class="text-center p-4 bg-yellow-50 rounded-lg">
                        <div class="text-2xl font-bold text-yellow-600"><?= number_format($loan['monthly_interest_rate'], 2, ',', '.') ?>%</div>
                        <div class="text-sm text-gray-600">Taxa de Juros</div>
                    </div>
                </div>
            </div>

            <!-- Informações do Cliente -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Informações do Cliente</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Nome</label>
                        <div class="text-lg text-gray-900"><?= esc($loan['client_name']) ?></div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">CPF</label>
                        <div class="text-lg text-gray-900"><?= esc($loan['client_cpf']) ?></div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Email</label>
                        <div class="text-lg text-gray-900"><?= esc($loan['client_email']) ?></div>
                    </div>
                </div>
            </div>

            <!-- Observações -->
            <?php if (!empty($loan['notes'])): ?>
                <div class="rounded-lg shadow-sm border border-gray-200 p-6" style="background-color: #FFFCE8;">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Observações</h2>
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="text-gray-900 whitespace-pre-wrap"><?= esc($loan['notes']) ?></div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Parcelas -->
            <?php if (!empty($installments)): ?>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Parcelas</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Parcela</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vencimento</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Valor</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pagamento</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($installments as $installment): ?>
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            <?= $installment['installment_number'] ?>ª
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?= $installment['due_date_formatted'] ?>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            <?= $installment['amount_formatted'] ?>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full <?= $installment['status_class'] ?>">
                                                <?= $installment['status_label'] ?>
                                            </span>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= $installment['paid_at_formatted'] ?? '-' ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar com Informações Adicionais -->
        <div class="space-y-6">
            <!-- Timeline -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Timeline</h2>
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-3 h-3 bg-blue-500 rounded-full mt-1"></div>
                        <div class="ml-3">
                            <div class="text-sm font-medium text-gray-900">Empréstimo Criado</div>
                            <div class="text-sm text-gray-500"><?= $loan['created_at_formatted'] ?></div>
                        </div>
                    </div>
                    
                    <?php if ($loan['accepted_at']): ?>
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-3 h-3 bg-green-500 rounded-full mt-1"></div>
                            <div class="ml-3">
                                <div class="text-sm font-medium text-gray-900">Aceito pelo Cliente</div>
                                <div class="text-sm text-gray-500"><?= $loan['accepted_at_formatted'] ?></div>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($loan['funded_at']): ?>
                        <div class="flex items-start">
                            <div class="flex-shrink-0 w-3 h-3 bg-purple-500 rounded-full mt-1"></div>
                            <div class="ml-3">
                                <div class="text-sm font-medium text-gray-900">Financiado</div>
                                <div class="text-sm text-gray-500"><?= $loan['funded_at_formatted'] ?></div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Token de Aceitação -->
            <?php if ($loan['status'] === 'pending_acceptance' && $loan['acceptance_token']): ?>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Token de Aceitação</h2>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Link de Aceitação</label>
                            <div class="mt-1 p-2 bg-gray-50 rounded text-sm break-all">
                                <?= base_url('/loans/accept/' . $loan['acceptance_token']) ?>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Expira em</label>
                            <div class="text-sm text-gray-900"><?= date('d/m/Y H:i', strtotime($loan['token_expires_at'])) ?></div>
                        </div>
                        <button onclick="copyAcceptanceLink()" 
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition-colors">
                            <i class="fas fa-copy mr-2"></i>Copiar Link
                        </button>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Informações Técnicas -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4">Informações Técnicas</h2>
                <div class="space-y-3 text-sm">
                    <div>
                        <span class="font-medium text-gray-500">ID:</span>
                        <span class="text-gray-900">#<?= $loan['id'] ?></span>
                    </div>
                    <?php if ($loan['funding_pix_transaction_id']): ?>
                        <div>
                            <span class="font-medium text-gray-500">PIX Transaction ID:</span>
                            <span class="text-gray-900 break-all"><?= esc($loan['funding_pix_transaction_id']) ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if ($loan['funded_by_user_id']): ?>
                        <div>
                            <span class="font-medium text-gray-500">Financiado por:</span>
                            <span class="text-gray-900">Usuário #<?= $loan['funded_by_user_id'] ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Financiamento -->
<div id="fundModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Financiar Empréstimo</h3>
                <form method="POST" action="<?= base_url('/loans/fund/' . $loan['id']) ?>">
                    <?= csrf_field() ?>
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
function showFundModal() {
    document.getElementById('fundModal').classList.remove('hidden');
}

function closeFundModal() {
    document.getElementById('fundModal').classList.add('hidden');
    document.getElementById('pix_transaction_id').value = '';
}

function confirmCancel() {
    if (confirm('Tem certeza que deseja cancelar este empréstimo?')) {
        window.location.href = '<?= base_url('/loans/cancel/' . $loan['id']) ?>';
    }
}

function copyAcceptanceLink() {
    const link = '<?= base_url('/loans/accept/' . $loan['acceptance_token']) ?>';
    navigator.clipboard.writeText(link).then(function() {
        alert('Link copiado para a área de transferência!');
    }, function(err) {
        console.error('Erro ao copiar link: ', err);
    });
}

// Fechar modal ao clicar fora
document.getElementById('fundModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeFundModal();
    }
});
</script>
<?= $this->endSection() ?>