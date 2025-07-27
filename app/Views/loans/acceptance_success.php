<?= $this->extend('layouts/public_layout') ?>

<?= $this->section('content') ?>
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div class="text-center">
                <!-- Logo -->
                <div class="mx-auto h-16 w-16 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-check text-green-600 text-2xl"></i>
                </div>
                
                <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                    <?= esc($title ?? 'Empréstimo Aceito!') ?>
                </h2>
                
                <p class="mt-2 text-sm text-gray-600">
                    <?= esc($message ?? 'Seu empréstimo foi aceito com sucesso.') ?>
                </p>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="space-y-4">
                    <div class="flex items-center p-4 bg-green-50 rounded-lg">
                        <i class="fas fa-info-circle text-green-600 mr-3"></i>
                        <div class="text-sm text-green-800">
                            <strong>Próximos passos:</strong><br>
                            <?= esc($nextSteps ?? 'Aguarde o financiamento do seu empréstimo. Você será notificado quando o valor estiver disponível.') ?>
                        </div>
                    </div>
                    
                    <div class="space-y-3">
                        <h3 class="text-lg font-medium text-gray-900">O que acontece agora?</h3>
                        
                        <div class="space-y-2">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center mt-0.5">
                                    <span class="text-blue-600 text-xs font-bold">1</span>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900">Análise Final</div>
                                    <div class="text-sm text-gray-500">Nossa equipe fará a análise final do seu empréstimo</div>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center mt-0.5">
                                    <span class="text-blue-600 text-xs font-bold">2</span>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900">Financiamento</div>
                                    <div class="text-sm text-gray-500">O valor será transferido para sua conta</div>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center mt-0.5">
                                    <span class="text-blue-600 text-xs font-bold">3</span>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900">Início do Pagamento</div>
                                    <div class="text-sm text-gray-500">As parcelas começarão a ser cobradas conforme acordado</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-200 pt-4">
                        <div class="text-sm text-gray-600">
                            <strong>Importante:</strong> Mantenha seus dados de contato atualizados. Entraremos em contato em caso de necessidade.
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center">
                <p class="text-sm text-gray-500">
                    Dúvidas? Entre em contato conosco:
                </p>
                <div class="mt-2 space-x-4">
                    <a href="mailto:contato@amxcred.com" class="text-blue-600 hover:text-blue-500 text-sm">
                        <i class="fas fa-envelope mr-1"></i>contato@amxcred.com
                    </a>
                    <a href="tel:+5511999999999" class="text-blue-600 hover:text-blue-500 text-sm">
                        <i class="fas fa-phone mr-1"></i>(11) 99999-9999
                    </a>
                </div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>