<?= $this->extend('layouts/public_layout') ?>

<?= $this->section('content') ?>
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <div class="bg-blue-600 px-6 py-4">
            <h2 class="text-xl font-bold text-white flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Confirmação de Empréstimo
            </h2>
        </div>
        <div class="p-6">
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-blue-800 font-medium">Atenção:</span>
                    <span class="text-blue-700 ml-1">Leia atentamente os termos do empréstimo antes de aceitar.</span>
                </div>
            </div>
            
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Detalhes do Empréstimo</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="text-sm text-gray-600 mb-1">Cliente</div>
                    <div class="font-medium text-gray-900"><?= esc($loan['client_name']) ?></div>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="text-sm text-gray-600 mb-1">Plano</div>
                    <div class="font-medium text-gray-900"><?= esc($loan['plan_name']) ?></div>
                </div>
                
                <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                    <div class="text-sm text-green-600 mb-1">Valor do Empréstimo</div>
                    <div class="font-bold text-green-700 text-lg"><?= esc($loan['loan_amount_formatted']) ?></div>
                </div>
                
                <div class="bg-red-50 p-4 rounded-lg border border-red-200">
                    <div class="text-sm text-red-600 mb-1">Valor Total a Pagar</div>
                    <div class="font-bold text-red-700 text-lg"><?= esc($loan['total_repayment_amount_formatted']) ?></div>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg">
                    <div class="text-sm text-gray-600 mb-1">Número de Parcelas</div>
                    <div class="font-medium text-gray-900"><?= esc($loan['number_of_installments']) ?>x</div>
                </div>
                
                <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                    <div class="text-sm text-blue-600 mb-1">Valor da Parcela</div>
                    <div class="font-bold text-blue-700 text-lg"><?= esc($loan['installment_amount_formatted']) ?></div>
                </div>
            </div>
            
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-yellow-800 font-medium">Link válido até:</span>
                    <span class="text-yellow-700 ml-2"><?= esc($loan['token_expires_at_formatted']) ?></span>
                </div>
            </div>
            
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Termos e Condições</h3>
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
                    <?php if (!empty($termsAndConditions)): ?>
                        <div class="text-sm text-gray-700 prose prose-sm max-w-none">
                            <?= $termsAndConditions ?>
                        </div>
                    <?php else: ?>
                        <!-- Termos padrão caso não haja configuração -->
                        <ul class="space-y-2 text-sm text-gray-700">
                            <li class="flex items-start">
                                <span class="w-2 h-2 bg-blue-600 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                Ao aceitar este empréstimo, você concorda em pagar o valor total em <?= esc($loan['number_of_installments']) ?> parcelas mensais.
                            </li>
                            <li class="flex items-start">
                                <span class="w-2 h-2 bg-blue-600 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                O valor de cada parcela é de <?= esc($loan['installment_amount_formatted']) ?>.
                            </li>
                            <li class="flex items-start">
                                <span class="w-2 h-2 bg-blue-600 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                O não pagamento das parcelas pode resultar em cobrança de juros e multas.
                            </li>
                            <li class="flex items-start">
                                <span class="w-2 h-2 bg-blue-600 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                Você tem o direito de quitar antecipadamente o empréstimo.
                            </li>
                            <li class="flex items-start">
                                <span class="w-2 h-2 bg-blue-600 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                Este empréstimo está sujeito às leis brasileiras de proteção ao consumidor.
                            </li>
                            <li class="flex items-start">
                                <span class="w-2 h-2 bg-blue-600 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                Em caso de dúvidas, entre em contato conosco antes de aceitar.
                            </li>
                        </ul>
                    <?php endif; ?>
                </div>
                
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                        <div>
                            <span class="text-yellow-800 font-medium">Importante:</span>
                            <span class="text-yellow-700 ml-1">Esta decisão é irreversível. Certifique-se de que pode cumprir com os pagamentos antes de aceitar.</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <form method="POST" action="<?= base_url('loans/process-acceptance') ?>" id="acceptanceForm">
                <input type="hidden" name="token" value="<?= esc($token) ?>">
                <input type="hidden" name="action" value="" id="actionInput">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                    <button type="button" class="bg-green-600 hover:bg-green-700 text-white font-bold py-4 px-6 rounded-lg transition duration-200 flex items-center justify-center" onclick="submitForm('accept')">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Aceitar Empréstimo
                    </button>
                    
                    <button type="button" class="bg-red-600 hover:bg-red-700 text-white font-bold py-4 px-6 rounded-lg transition duration-200 flex items-center justify-center" onclick="submitForm('reject')">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Recusar Empréstimo
                    </button>
                </div>
            </form>
            
            <div class="text-center">
                <div class="flex items-center justify-center text-sm text-gray-500">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    Suas informações estão protegidas e este processo é seguro.
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de Confirmação -->
<div id="confirmationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <div id="modalIcon" class="mx-auto flex items-center justify-center h-12 w-12 rounded-full mb-4">
                <!-- Ícone será inserido dinamicamente -->
            </div>
            <h3 id="modalTitle" class="text-lg leading-6 font-medium text-gray-900 mb-2"></h3>
            <div class="mt-2 px-7 py-3">
                <p id="modalMessage" class="text-sm text-gray-500"></p>
            </div>
            <div class="items-center px-4 py-3">
                <button id="confirmButton" class="px-4 py-2 text-white text-base font-medium rounded-md w-24 mr-2 transition-colors">
                    Sim
                </button>
                <button id="cancelButton" class="px-4 py-2 bg-gray-300 text-gray-800 text-base font-medium rounded-md w-24 hover:bg-gray-400 transition-colors" onclick="closeModal()">
                    Cancelar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let currentAction = null;

function submitForm(action) {
    currentAction = action;
    const modal = document.getElementById('confirmationModal');
    const modalIcon = document.getElementById('modalIcon');
    const modalTitle = document.getElementById('modalTitle');
    const modalMessage = document.getElementById('modalMessage');
    const confirmButton = document.getElementById('confirmButton');
    
    if (action === 'accept') {
        modalIcon.innerHTML = '<svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
        modalIcon.className = 'mx-auto flex items-center justify-center h-12 w-12 rounded-full mb-4 bg-green-100';
        modalTitle.textContent = 'Aceitar Empréstimo';
        modalMessage.textContent = 'Você tem certeza de que deseja ACEITAR este empréstimo? Esta ação não pode ser desfeita e você se compromete a pagar todas as parcelas conforme acordado.';
        confirmButton.className = 'px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-base font-medium rounded-md w-24 mr-2 transition-colors';
        confirmButton.textContent = 'Aceitar';
    } else if (action === 'reject') {
        modalIcon.innerHTML = '<svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';
        modalIcon.className = 'mx-auto flex items-center justify-center h-12 w-12 rounded-full mb-4 bg-red-100';
        modalTitle.textContent = 'Recusar Empréstimo';
        modalMessage.textContent = 'Você tem certeza de que deseja RECUSAR este empréstimo? Esta oportunidade não estará mais disponível após a recusa.';
        confirmButton.className = 'px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-base font-medium rounded-md w-24 mr-2 transition-colors';
        confirmButton.textContent = 'Recusar';
    }
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    const modal = document.getElementById('confirmationModal');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
    currentAction = null;
}

function confirmAction() {
    if (currentAction) {
        // Mostrar loading
        const confirmButton = document.getElementById('confirmButton');
        const originalText = confirmButton.textContent;
        confirmButton.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Processando...';
        confirmButton.disabled = true;
        
        document.getElementById('actionInput').value = currentAction;
        document.getElementById('acceptanceForm').submit();
    }
}

// Event listener para o botão de confirmação
document.getElementById('confirmButton').addEventListener('click', confirmAction);

// Fechar modal ao clicar fora dele
document.getElementById('confirmationModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

// Fechar modal com ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
    }
});
</script>
<?= $this->endSection() ?>