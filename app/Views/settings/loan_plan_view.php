<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('content') ?>
<div class="max-w-4xl mx-auto">
    <!-- Page Header -->
    <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900"><?= esc($plan['name']) ?></h1>
                <p class="mt-1 text-sm text-gray-600">Detalhes completos do plano de empréstimo</p>
            </div>
            <div class="flex space-x-3">
                <a href="<?= base_url('/settings/loan-plans/edit/' . $plan['id']) ?>" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Editar
                </a>
                <a href="<?= base_url('/settings/loan-plans') ?>" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
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
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        Plano Ativo
                    </span>
                <?php else: ?>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                        Plano Inativo
                    </span>
                <?php endif; ?>
            </div>

            <!-- Main Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-blue-600">Valor do Empréstimo</div>
                            <div class="text-2xl font-bold text-blue-900">R$ <?= number_format($plan['loan_amount'], 2, ',', '.') ?></div>
                            <div class="text-sm text-blue-600">Valor depositado na conta do cliente</div>
                        </div>
                    </div>
                </div>

                <div class="bg-green-50 p-4 rounded-lg">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-green-600">Total a Pagar</div>
                            <div class="text-2xl font-bold text-green-900">R$ <?= number_format($plan['total_repayment_amount'], 2, ',', '.') ?></div>
                            <div class="text-sm text-green-600">Soma de todas as parcelas</div>
                        </div>
                    </div>
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

            <!-- Simulation Table -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Simulação de Parcelas</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Parcela
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Valor
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Vencimento*
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php
                            // Proteção máxima contra loops infinitos
                            $installments = isset($plan['number_of_installments']) ? (int)$plan['number_of_installments'] : 1;
                            $installments = max(1, min($installments, 12)); // Máximo 12 parcelas na simulação
                            
                            // Usar timestamp simples em vez de DateTime para evitar problemas
                            $currentMonth = date('n');
                            $currentYear = date('Y');
                            
                            for ($i = 1; $i <= $installments; $i++):
                                $month = $currentMonth + $i;
                                $year = $currentYear;
                                
                                // Ajustar ano se necessário
                                while ($month > 12) {
                                    $month -= 12;
                                    $year++;
                                }
                                
                                $dueDate = sprintf('%02d/%02d/%d', 15, $month, $year);
                            ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        <?= str_pad($i, 2, '0', STR_PAD_LEFT) ?>/<?= str_pad($plan['number_of_installments'], 2, '0', STR_PAD_LEFT) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        R$ <?= number_format($plan['installment_amount'], 2, ',', '.') ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= $dueDate ?>
                                    </td>
                                </tr>
                            <?php endfor; ?>
                            
                            <?php if ($plan['number_of_installments'] > 12): ?>
                                <tr class="hover:bg-gray-50">
                                    <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500 italic">
                                        ... e mais <?= $plan['number_of_installments'] - 12 ?> parcelas
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                <p class="mt-2 text-sm text-gray-500">* Datas de vencimento são simuladas considerando parcelas mensais a partir de hoje</p>
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