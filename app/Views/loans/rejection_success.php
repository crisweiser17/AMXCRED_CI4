<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <!-- Ícone de Sucesso -->
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-orange-100 mb-6">
                <svg class="h-6 w-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>
            
            <!-- Título -->
            <div class="text-center">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">
                    <?= esc($title ?? 'Empréstimo Recusado') ?>
                </h2>
                
                <!-- Mensagem Principal -->
                <p class="text-gray-600 mb-6">
                    <?= esc($message ?? 'Empréstimo recusado. Obrigado pelo seu tempo.') ?>
                </p>
                
                <!-- Informações Adicionais -->
                <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-orange-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-orange-800">
                                Empréstimo não aceito
                            </h3>
                            <div class="mt-2 text-sm text-orange-700">
                                <p>Você optou por não aceitar este empréstimo. Caso mude de ideia, entre em contato conosco.</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Botões de Ação -->
                <div class="space-y-3">
                    <a href="<?= base_url('/dashboard') ?>" 
                       class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Voltar ao Dashboard
                    </a>
                    
                    <a href="<?= base_url('/loans') ?>" 
                       class="w-full flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Ver Outros Empréstimos
                    </a>
                </div>
                
                <!-- Informações de Contato -->
                <div class="mt-8 pt-6 border-t border-gray-200">
                    <p class="text-xs text-gray-500 text-center">
                        Dúvidas? Entre em contato conosco:<br>
                        <strong>WhatsApp:</strong> (11) 99999-9999<br>
                        <strong>E-mail:</strong> suporte@amxcred.com.br
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>