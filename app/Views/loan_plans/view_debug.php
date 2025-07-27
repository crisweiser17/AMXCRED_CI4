<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('content') ?>
<div class="max-w-4xl mx-auto">
    <!-- Page Header -->
    <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">🔧 DEBUG - <?= esc($plan['name']) ?></h1>
                <p class="mt-1 text-sm text-gray-600">Visualização de debug independente</p>
            </div>
            <div class="flex space-x-3">
                <a href="/loan-plans-debug" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    Voltar
                </a>
            </div>
        </div>
    </div>

    <!-- Debug Info -->
    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
        <div class="flex">
            <div class="ml-3">
                <h3 class="text-sm font-medium text-yellow-800">
                    Informações de Debug
                </h3>
                <div class="mt-2 text-sm text-yellow-700">
                    <p><strong>ID do Plano:</strong> <?= $plan['id'] ?></p>
                    <p><strong>Controller:</strong> LoanPlansController (independente)</p>
                    <p><strong>View:</strong> loan_plans/view_debug.php</p>
                    <p><strong>Sem loops:</strong> Dados estáticos, sem cálculos complexos</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Plan Details -->
    <div class="bg-white shadow-sm rounded-lg">
        <div class="p-6">
            <!-- Status Badge -->
            <div class="mb-6">
                <?php if ($plan['is_active']): ?>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        Plano Ativo
                    </span>
                <?php else: ?>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                        Plano Inativo
                    </span>
                <?php endif; ?>
            </div>

            <!-- Main Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <div class="text-sm font-medium text-blue-600">Valor do Empréstimo</div>
                    <div class="text-2xl font-bold text-blue-900">R$ <?= number_format($plan['loan_amount'], 2, ',', '.') ?></div>
                </div>

                <div class="bg-green-50 p-4 rounded-lg">
                    <div class="text-sm font-medium text-green-600">Total a Pagar</div>
                    <div class="text-2xl font-bold text-green-900">R$ <?= number_format($plan['total_repayment_amount'], 2, ',', '.') ?></div>
                </div>
            </div>

            <!-- Simple Calculations -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
                <div class="bg-gray-50 p-4 rounded-lg text-center">
                    <div class="text-2xl font-bold text-gray-900"><?= $plan['number_of_installments'] ?>x</div>
                    <div class="text-sm font-medium text-gray-600">Parcelas</div>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg text-center">
                    <div class="text-2xl font-bold text-gray-900">R$ <?= number_format($plan['total_repayment_amount'] / $plan['number_of_installments'], 2, ',', '.') ?></div>
                    <div class="text-sm font-medium text-gray-600">Valor da Parcela</div>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg text-center">
                    <div class="text-2xl font-bold text-gray-900">R$ <?= number_format($plan['total_repayment_amount'] - $plan['loan_amount'], 2, ',', '.') ?></div>
                    <div class="text-sm font-medium text-gray-600">Total de Juros</div>
                </div>
            </div>

            <!-- Raw Data -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Dados Brutos (Debug)</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <pre class="text-sm text-gray-700"><?= json_encode($plan, JSON_PRETTY_PRINT) ?></pre>
                </div>
            </div>

            <!-- Test Navigation -->
            <div class="border-t border-gray-200 pt-6 mt-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Teste de Navegação</h3>
                <div class="flex space-x-3">
                    <a href="/loan-plans-debug" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                        Voltar para Lista
                    </a>
                    <a href="/loan-plans-debug/view/<?= $plan['id'] ?>" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                        Recarregar Esta Página
                    </a>
                </div>
                <p class="mt-2 text-sm text-gray-500">Teste clicando várias vezes para verificar se há loops</p>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>