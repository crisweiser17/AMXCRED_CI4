<?= $this->extend('layouts/public_layout') ?>

<?= $this->section('content') ?>
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div class="text-center">
                <!-- Logo -->
                <div class="mx-auto h-16 w-16 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-times text-red-600 text-2xl"></i>
                </div>
                
                <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                    <?= esc($title ?? 'Erro na Aceitação') ?>
                </h2>
                
                <p class="mt-2 text-sm text-gray-600">
                    <?= esc($message ?? 'Não foi possível aceitar o empréstimo.') ?>
                </p>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="space-y-4">
                    <!-- Mensagem de Erro -->
                    <?php if (session('error')): ?>
                        <div class="flex items-center p-4 bg-red-50 rounded-lg">
                            <i class="fas fa-exclamation-triangle text-red-600 mr-3"></i>
                            <div class="text-sm text-red-800">
                                <?= session('error') ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="space-y-3">
                        <h3 class="text-lg font-medium text-gray-900">Possíveis motivos:</h3>
                        
                        <div class="space-y-2">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-2 h-2 bg-gray-400 rounded-full mt-2"></div>
                                <div class="ml-3">
                                    <div class="text-sm text-gray-700">Link de aceitação expirado</div>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-2 h-2 bg-gray-400 rounded-full mt-2"></div>
                                <div class="ml-3">
                                    <div class="text-sm text-gray-700">Empréstimo já foi aceito anteriormente</div>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-2 h-2 bg-gray-400 rounded-full mt-2"></div>
                                <div class="ml-3">
                                    <div class="text-sm text-gray-700">Empréstimo foi cancelado</div>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="flex-shrink-0 w-2 h-2 bg-gray-400 rounded-full mt-2"></div>
                                <div class="ml-3">
                                    <div class="text-sm text-gray-700">Link inválido ou corrompido</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-200 pt-4">
                        <div class="space-y-3">
                            <h4 class="text-base font-medium text-gray-900">O que fazer agora?</h4>
                            
                            <div class="text-sm text-gray-600 space-y-2">
                                <p>1. Verifique se o link está correto e completo</p>
                                <p>2. Entre em contato conosco para solicitar um novo link</p>
                                <p>3. Confirme se o empréstimo ainda está disponível</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center space-y-4">
                <div>
                    <button onclick="history.back()" 
                            class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Voltar
                    </button>
                </div>
                
                <div>
                    <p class="text-sm text-gray-500 mb-2">
                        Precisa de ajuda? Entre em contato conosco:
                    </p>
                    <div class="space-x-4">
                        <a href="mailto:contato@amxcred.com" class="text-blue-600 hover:text-blue-500 text-sm">
                            <i class="fas fa-envelope mr-1"></i>contato@amxcred.com
                        </a>
                        <a href="tel:+5511999999999" class="text-blue-600 hover:text-blue-500 text-sm">
                            <i class="fas fa-phone mr-1"></i>(11) 99999-9999
                        </a>
                    </div>
                </div>
                
                <div class="text-xs text-gray-400">
                    <p>AMXCred - Soluções Financeiras</p>
                </div>
            </div>
        </div>
    </div>
<?= $this->endSection() ?>