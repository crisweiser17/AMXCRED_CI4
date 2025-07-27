<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('content') ?>
<div class="max-w-4xl mx-auto">
    <!-- Page Header -->
    <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900"><?= esc($plan['name']) ?></h1>
                <p class="mt-1 text-sm text-gray-600">Detalhes do plano de empréstimo</p>
            </div>
            <div class="flex space-x-3">
                <a href="<?= base_url('/settings/loan-plans/edit/' . $plan['id']) ?>" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                    Editar
                </a>
                <a href="<?= base_url('/settings/loan-plans') ?>" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    Voltar
                </a>
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

            <!-- Detailed Calculations -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-gray-50 p-4 rounded-lg text-center">
                    <div class="text-2xl font-bold text-gray-900"><?= $plan['number_of_installments'] ?>x</div>
                    <div class="text-sm font-medium text-gray-600">Parcelas</div>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg text-center">
                    <div class="text-2xl font-bold text-gray-900">R$ <?= number_format($plan['installment_amount'], 2, ',', '.') ?></div>
                    <div class="text-sm font-medium text-gray-600">Valor da Parcela</div>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg text-center">
                    <div class="text-2xl font-bold text-gray-900">R$ <?= number_format($plan['total_interest'], 2, ',', '.') ?></div>
                    <div class="text-sm font-medium text-gray-600">Total de Juros</div>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg text-center">
                    <div class="text-2xl font-bold text-gray-900"><?= number_format($plan['monthly_interest_rate'], 2, ',', '.') ?>%</div>
                    <div class="text-sm font-medium text-gray-600">Juros Mensal</div>
                </div>
            </div>

            <!-- Simple Simulation -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Resumo do Plano</h3>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                        <div>
                            <div class="text-lg font-semibold text-gray-900"><?= $plan['number_of_installments'] ?> parcelas</div>
                            <div class="text-sm text-gray-600">de R$ <?= number_format($plan['installment_amount'], 2, ',', '.') ?></div>
                        </div>
                        <div>
                            <div class="text-lg font-semibold text-gray-900">Taxa <?= number_format($plan['monthly_interest_rate'], 2, ',', '.') ?>%</div>
                            <div class="text-sm text-gray-600">ao mês</div>
                        </div>
                        <div>
                            <div class="text-lg font-semibold text-gray-900">Total R$ <?= number_format($plan['total_repayment_amount'], 2, ',', '.') ?></div>
                            <div class="text-sm text-gray-600">a pagar</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Metadata -->
            <div class="border-t border-gray-200 pt-6">
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Data de Criação</dt>
                        <dd class="mt-1 text-sm text-gray-900"><?= date('d/m/Y H:i', strtotime($plan['created_at'])) ?></dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Última Atualização</dt>
                        <dd class="mt-1 text-sm text-gray-900"><?= date('d/m/Y H:i', strtotime($plan['updated_at'])) ?></dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>