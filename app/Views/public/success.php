<?= $this->extend('layouts/public_layout') ?>

<?= $this->section('content') ?>
<div class="max-w-2xl mx-auto px-4">
    <div class="bg-white shadow rounded-lg p-8 text-center">
        <!-- Success Icon -->
        <div class="mb-6">
            <div class="mx-auto w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
        
        <!-- Success Message -->
        <h1 class="text-3xl font-bold text-gray-900 mb-4">Cadastro Realizado com Sucesso!</h1>
        
        <p class="text-lg text-gray-600 mb-6">
            Obrigado por se cadastrar na AMX Cred. Recebemos seus dados e documentos com segurança.
        </p>
        
        <!-- Next Steps -->
        <div class="bg-blue-50 rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold text-blue-900 mb-4">Próximos Passos:</h2>
            <div class="space-y-4">
                <div class="flex items-start text-left">
                    <div class="flex-shrink-0 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-semibold mr-4">
                        1
                    </div>
                    <div>
                        <h3 class="font-semibold text-blue-900">Análise de Documentos</h3>
                        <p class="text-blue-800 text-sm">Nossa equipe analisará seus documentos em até 24 horas úteis</p>
                    </div>
                </div>
                
                <div class="flex items-start text-left">
                    <div class="flex-shrink-0 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-semibold mr-4">
                        2
                    </div>
                    <div>
                        <h3 class="font-semibold text-blue-900">Verificação de Dados</h3>
                        <p class="text-blue-800 text-sm">Realizaremos a consulta e verificação dos seus dados</p>
                    </div>
                </div>
                
                <div class="flex items-start text-left">
                    <div class="flex-shrink-0 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center text-sm font-semibold mr-4">
                        3
                    </div>
                    <div>
                        <h3 class="font-semibold text-blue-900">Retorno por Email</h3>
                        <p class="text-blue-800 text-sm">Você receberá uma notificação por email sobre o status da sua solicitação</p>
                    </div>
                </div>
                
                <div class="flex items-start text-left">
                    <div class="flex-shrink-0 w-8 h-8 bg-green-600 text-white rounded-full flex items-center justify-center text-sm font-semibold mr-4">
                        4
                    </div>
                    <div>
                        <h3 class="font-semibold text-green-900">Aprovação e Contato</h3>
                        <p class="text-green-800 text-sm">Se aprovado, entraremos em contato para finalizar o processo do empréstimo</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Important Information -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-yellow-400 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <div class="text-left">
                    <h3 class="text-sm font-medium text-yellow-800">Importante</h3>
                    <p class="text-sm text-yellow-700 mt-1">
                        Mantenha seu telefone e email atualizados. Caso não receba retorno em 48 horas, 
                        entre em contato conosco pelos canais abaixo.
                    </p>
                </div>
            </div>
        </div>
        
        <!-- Contact Information -->
        <div class="border-t border-gray-200 pt-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Precisa de Ajuda?</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex items-center justify-center space-x-3 p-4 bg-green-50 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <div class="text-left">
                        <p class="font-semibold text-green-900">WhatsApp</p>
                        <p class="text-sm text-green-700">(11) 99999-9999</p>
                    </div>
                </div>
                
                <div class="flex items-center justify-center space-x-3 p-4 bg-blue-50 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <div class="text-left">
                        <p class="font-semibold text-blue-900">Email</p>
                        <p class="text-sm text-blue-700">contato@amxcred.com.br</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="mt-8 space-y-4">
            <div class="text-sm text-gray-600">
                <p>Guarde este número para acompanhar sua solicitação:</p>
                <p class="font-mono text-lg font-semibold text-blue-600 mt-1">
                    #<?= str_pad(rand(100000, 999999), 6, '0', STR_PAD_LEFT) ?>
                </p>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="<?= base_url('/register') ?>" 
                   class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                    Fazer Novo Cadastro
                </a>
                <a href="https://wa.me/5511999999999?text=Olá! Acabei de fazer meu cadastro na AMX Cred e gostaria de acompanhar o status." 
                   target="_blank"
                   class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors inline-flex items-center justify-center">
                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                    </svg>
                    Falar no WhatsApp
                </a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>