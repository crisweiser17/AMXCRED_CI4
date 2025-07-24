/**
 * File Preview Handler
 * Centraliza as funções de preview de arquivos para evitar duplicação
 */

class FilePreviewHandler {
    constructor() {
        this.init();
    }

    init() {
        // Inicializar preview para todos os inputs de arquivo
        const fileInputs = document.querySelectorAll('input[type="file"]');
        fileInputs.forEach(input => {
            input.addEventListener('change', (e) => this.handleFileChange(e));
        });

        // Tornar funções globais para uso nos botões
        window.removeFilePreview = (input) => this.removeFilePreview(input);
    }

    handleFileChange(e) {
        const input = e.target;
        const file = input.files[0];
        
        if (file) {
            // Verificar tamanho do arquivo (10MB)
            if (file.size > 10 * 1024 * 1024) {
                alert('Arquivo muito grande. Tamanho máximo: 10MB');
                input.value = '';
                return;
            }
            
            // Verificar tipo do arquivo
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf'];
            if (!allowedTypes.includes(file.type)) {
                alert('Tipo de arquivo não permitido. Use apenas JPG, PNG ou PDF.');
                input.value = '';
                return;
            }
            
            // Criar preview
            this.createFilePreview(input, file);
        } else {
            // Remover preview se arquivo foi removido
            this.removeFilePreview(input);
        }
    }

    createFilePreview(input, file) {
        // Remover preview anterior se existir
        this.removeFilePreview(input);
        
        const previewContainer = document.createElement('div');
        previewContainer.className = 'file-preview mt-2 mb-2';
        
        if (file.type.startsWith('image/')) {
            // Preview para imagens
            const reader = new FileReader();
            reader.onload = (e) => {
                previewContainer.innerHTML = `
                    <div class="relative inline-block">
                        <img src="${e.target.result}"
                             alt="Preview"
                             class="w-full h-32 object-cover rounded-lg border border-gray-200">
                        <button type="button"
                                onclick="removeFilePreview(document.getElementById('${input.id}'), true)"
                                class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="mt-2 text-xs text-gray-600">
                        <p>Arquivo: ${file.name}</p>
                        <p>Tamanho: ${this.formatFileSize(file.size)}</p>
                    </div>
                `;
            };
            reader.readAsDataURL(file);
        } else if (file.type === 'application/pdf') {
            // Preview para PDFs
            previewContainer.innerHTML = `
                <div class="relative inline-block">
                    <div class="w-full h-32 bg-gray-100 rounded-lg border border-gray-200 flex items-center justify-center">
                        <div class="text-center">
                            <svg class="w-12 h-12 text-red-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="text-sm font-medium text-gray-700">PDF</p>
                        </div>
                    </div>
                    <button type="button"
                            onclick="removeFilePreview(document.getElementById('${input.id}'), true)"
                            class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center hover:bg-red-600">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <div class="mt-2 text-xs text-gray-600">
                    <p>Arquivo: ${file.name}</p>
                    <p>Tamanho: ${this.formatFileSize(file.size)}</p>
                </div>
            `;
        }
        
        // Inserir preview antes do input
        input.parentNode.insertBefore(previewContainer, input);
    }

    removeFilePreview(input) {
        const existingPreview = input.parentNode.querySelector('.file-preview');
        if (existingPreview) {
            existingPreview.remove();
        }
        // Só limpar o valor se foi chamado explicitamente pelo botão de remoção
        // Não limpar automaticamente para não interferir no envio do formulário
        if (arguments.length > 1 && arguments[1] === true) {
            input.value = '';
        }
    }

    formatFileSize(bytes) {
        const units = ['B', 'KB', 'MB', 'GB'];
        let size = bytes;
        let unitIndex = 0;
        
        while (size >= 1024 && unitIndex < units.length - 1) {
            size /= 1024;
            unitIndex++;
        }
        
        return `${size.toFixed(2)} ${units[unitIndex]}`;
    }
}

// Inicializar quando o DOM estiver carregado
document.addEventListener('DOMContentLoaded', function() {
    new FilePreviewHandler();
});