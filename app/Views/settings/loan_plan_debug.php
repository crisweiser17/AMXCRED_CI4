<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('content') ?>
<div class="max-w-4xl mx-auto">
    <!-- Page Header -->
    <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">DEBUG - Plano de Empréstimo</h1>
                <p class="mt-1 text-sm text-gray-600">Versão de debug sem loops</p>
            </div>
            <div class="flex space-x-3">
                <a href="/settings/loan-plans" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
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
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                    Plano de Teste - DEBUG
                </span>
            </div>

            <!-- Main Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-blue-50 p-4 rounded-lg">
                    <div class="text-sm font-medium text-blue-600">Valor do Empréstimo</div>
                    <div class="text-2xl font-bold text-blue-900">R$ 1.000,00</div>
                </div>

                <div class="bg-green-50 p-4 rounded-lg">
                    <div class="text-sm font-medium text-green-600">Total a Pagar</div>
                    <div class="text-2xl font-bold text-green-900">R$ 1.200,00</div>
                </div>
            </div>

            <!-- Detailed Calculations -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                <div class="bg-gray-50 p-4 rounded-lg text-center">
                    <div class="text-2xl font-bold text-gray-900">12x</div>
                    <div class="text-sm font-medium text-gray-600">Parcelas</div>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg text-center">
                    <div class="text-2xl font-bold text-gray-900">R$ 100,00</div>
                    <div class="text-sm font-medium text-gray-600">Valor da Parcela</div>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg text-center">
                    <div class="text-2xl font-bold text-gray-900">R$ 200,00</div>
                    <div class="text-sm font-medium text-gray-600">Total de Juros</div>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg text-center">
                    <div class="text-2xl font-bold text-gray-900">1,50%</div>
                    <div class="text-sm font-medium text-gray-600">Juros Mensal</div>
                </div>
            </div>

            <!-- Simple Message -->
            <div class="mb-8">
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <div class="flex">
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-yellow-800">
                                Versão de Debug
                            </h3>
                            <div class="mt-2 text-sm text-yellow-700">
                                <p>Esta é uma versão simplificada para identificar problemas. Se esta página carregar sem problemas, o erro está no código original.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Test Info -->
            <div class="border-t border-gray-200 pt-6">
                <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">ID do Plano</dt>
                        <dd class="mt-1 text-sm text-gray-900"><?= $plan['id'] ?></dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Status</dt>
                        <dd class="mt-1 text-sm text-gray-900">Debug Mode</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>