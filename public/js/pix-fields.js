/**
 * PIX Fields Handler
 * Gerencia o relacionamento entre os campos Tipo de Chave PIX e Chave PIX
 * 
 * @author AMX Cred
 * @version 1.0.0
 */

class PixFieldsHandler {
    constructor(options = {}) {
        // Configurações padrão
        this.config = {
            pixKeyTypeSelector: options.pixKeyTypeSelector || '#pix_key_type',
            pixKeySelector: options.pixKeySelector || '#pix_key',
            cpfSelector: options.cpfSelector || '#cpf',
            emailSelector: options.emailSelector || '#email',
            phoneSelector: options.phoneSelector || '#phone',
            ...options
        };
        
        this.pixKeyTypeElement = null;
        this.pixKeyElement = null;
        this.cpfElement = null;
        this.emailElement = null;
        this.phoneElement = null;
        
        this.init();
    }
    
    /**
     * Inicializa o handler
     */
    init() {
        // Aguarda o DOM estar carregado
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => this.setup());
        } else {
            this.setup();
        }
    }
    
    /**
     * Configura os elementos e event listeners
     */
    setup() {
        // Busca os elementos
        this.pixKeyTypeElement = document.querySelector(this.config.pixKeyTypeSelector);
        this.pixKeyElement = document.querySelector(this.config.pixKeySelector);
        this.cpfElement = document.querySelector(this.config.cpfSelector);
        this.emailElement = document.querySelector(this.config.emailSelector);
        this.phoneElement = document.querySelector(this.config.phoneSelector);
        
        // Verifica se os elementos obrigatórios existem
        if (!this.pixKeyTypeElement || !this.pixKeyElement) {
            console.warn('PIX Fields Handler: Elementos obrigatórios não encontrados');
            return;
        }
        
        // Configura event listeners
        this.setupEventListeners();
        
        // Inicializa o estado baseado no valor atual
        this.updatePixKeyField();
    }
    
    /**
     * Configura os event listeners
     */
    setupEventListeners() {
        // Listener para mudança no tipo de chave PIX
        this.pixKeyTypeElement.addEventListener('change', () => {
            this.updatePixKeyField();
        });
        
        // Listener para mudança no CPF (atualizar chave PIX se tipo for CPF)
        if (this.cpfElement) {
            this.cpfElement.addEventListener('input', () => {
                if (this.pixKeyTypeElement.value === 'cpf') {
                    this.pixKeyElement.value = this.cpfElement.value;
                }
            });
            
            this.cpfElement.addEventListener('blur', () => {
                this.updatePixKeyIfCpfType();
            });
        }
        
        // Listener para aplicar máscaras no campo PIX baseado no tipo
        this.pixKeyElement.addEventListener('input', (e) => {
            this.applyPixKeyMask(e);
        });
        
        // Listener para validação em tempo real
        this.pixKeyElement.addEventListener('blur', () => {
            this.validatePixKeyRealTime();
        });
    }
    
    /**
     * Atualiza o campo de chave PIX baseado no tipo selecionado
     */
    updatePixKeyField() {
        const selectedType = this.pixKeyTypeElement.value;
        
        // Reset do campo
        this.resetPixKeyField();
        
        switch (selectedType) {
            case 'cpf':
                this.handleCpfType();
                break;
            case 'email':
                this.handleEmailType();
                break;
            case 'phone':
                this.handlePhoneType();
                break;
            case 'random':
                this.handleRandomType();
                break;
            default:
                this.handleDefaultType();
        }
    }
    
    /**
     * Reseta o campo de chave PIX para o estado padrão
     */
    resetPixKeyField() {
        this.pixKeyElement.readOnly = false;
        this.pixKeyElement.classList.remove('bg-gray-100', 'cursor-not-allowed', 'border-red-500', 'border-green-500');
        this.pixKeyElement.placeholder = 'Digite a chave PIX';
    }
    
    /**
     * Configura o campo para tipo CPF
     */
    handleCpfType() {
        if (this.cpfElement && this.cpfElement.value) {
            this.pixKeyElement.value = this.cpfElement.value;
        }
        this.pixKeyElement.readOnly = true;
        this.pixKeyElement.classList.add('bg-gray-100', 'cursor-not-allowed');
        this.pixKeyElement.placeholder = 'CPF será preenchido automaticamente';
    }
    
    /**
     * Configura o campo para tipo Email
     */
    handleEmailType() {
        // Remove máscara e configura para email
        this.pixKeyElement.placeholder = 'Digite o email (ex: usuario@email.com)';
        this.pixKeyElement.classList.remove('border-red-500');
        // Não altera o valor atual do campo
    }
    
    /**
     * Configura o campo para tipo Telefone
     */
    handlePhoneType() {
        // Remove máscara e configura para telefone
        this.pixKeyElement.placeholder = 'Digite o telefone (ex: (11) 99999-9999)';
        this.pixKeyElement.classList.remove('border-red-500');
        // Não altera o valor atual do campo
    }
    
    /**
     * Configura o campo para tipo Chave Aleatória
     */
    handleRandomType() {
        this.pixKeyElement.placeholder = 'Digite a chave aleatória';
    }
    
    /**
     * Configura o campo para tipo padrão
     */
    handleDefaultType() {
        this.pixKeyElement.placeholder = 'Digite a chave PIX';
    }
    
    /**
     * Atualiza chave PIX se o tipo for CPF
     */
    updatePixKeyIfCpfType() {
        if (this.pixKeyTypeElement.value === 'cpf' && this.cpfElement && this.cpfElement.value) {
            this.pixKeyElement.value = this.cpfElement.value;
        }
    }
    
    /**
     * Limpa o campo de chave PIX se estava preenchido automaticamente
     */
    clearAutoFilledValue() {
        const selectedType = this.pixKeyTypeElement.value;
        const currentValue = this.pixKeyElement.value;
        
        // Apenas limpa se mudou de CPF para outro tipo e o valor atual é igual ao CPF
        if (selectedType !== 'cpf' && this.cpfElement && currentValue === this.cpfElement.value) {
            this.pixKeyElement.value = '';
        }
    }
    
    /**
     * Obtém os valores atuais dos campos PIX
     * @returns {Object} Objeto com os valores dos campos
     */
    getValues() {
        return {
            pixKeyType: this.pixKeyTypeElement ? this.pixKeyTypeElement.value : '',
            pixKey: this.pixKeyElement ? this.pixKeyElement.value : ''
        };
    }
    
    /**
     * Define os valores dos campos PIX
     * @param {Object} values - Objeto com os valores a serem definidos
     */
    setValues(values) {
        if (values.pixKeyType && this.pixKeyTypeElement) {
            this.pixKeyTypeElement.value = values.pixKeyType;
        }
        
        if (values.pixKey && this.pixKeyElement) {
            this.pixKeyElement.value = values.pixKey;
        }
        
        // Atualiza o campo baseado no tipo para configurar corretamente
        this.updatePixKeyField();
    }
    
    /**
     * Valida os campos PIX
     * @returns {Object} Resultado da validação
     */
    validate() {
        const values = this.getValues();
        const errors = [];
        
        // Se nenhum campo PIX foi preenchido, considera válido
        if (!values.pixKeyType && !values.pixKey) {
            return {
                isValid: true,
                errors: []
            };
        }
        
        // Se um campo foi preenchido, ambos devem ser preenchidos
        if (!values.pixKeyType) {
            errors.push('Tipo de chave PIX é obrigatório quando chave PIX é informada');
        }
        
        if (!values.pixKey) {
            errors.push('Chave PIX é obrigatória quando tipo de chave PIX é informado');
        }
        
        // Validações específicas por tipo
        if (values.pixKeyType && values.pixKey) {
            switch (values.pixKeyType) {
                case 'cpf':
                    if (!this.isValidCPF(values.pixKey)) {
                        errors.push('CPF inválido para chave PIX');
                    }
                    break;
                case 'email':
                    if (!this.isValidEmail(values.pixKey)) {
                        errors.push('Email inválido para chave PIX');
                    }
                    break;
                case 'phone':
                    if (!this.isValidPhone(values.pixKey)) {
                        errors.push('Telefone inválido para chave PIX');
                    }
                    break;
            }
        }
        
        return {
            isValid: errors.length === 0,
            errors: errors
        };
    }
    
    /**
     * Valida CPF
     * @param {string} cpf - CPF a ser validado
     * @returns {boolean} True se válido
     */
    isValidCPF(cpf) {
        cpf = cpf.replace(/[^\d]+/g, '');
        if (cpf.length !== 11 || /^(\d)\1{10}$/.test(cpf)) return false;
        
        let soma = 0;
        for (let i = 0; i < 9; i++) {
            soma += parseInt(cpf.charAt(i)) * (10 - i);
        }
        let resto = 11 - (soma % 11);
        if (resto === 10 || resto === 11) resto = 0;
        if (resto !== parseInt(cpf.charAt(9))) return false;
        
        soma = 0;
        for (let i = 0; i < 10; i++) {
            soma += parseInt(cpf.charAt(i)) * (11 - i);
        }
        resto = 11 - (soma % 11);
        if (resto === 10 || resto === 11) resto = 0;
        return resto === parseInt(cpf.charAt(10));
    }
    
    /**
     * Valida email
     * @param {string} email - Email a ser validado
     * @returns {boolean} True se válido
     */
    isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
    
    /**
     * Valida telefone
     * @param {string} phone - Telefone a ser validado
     * @returns {boolean} True se válido
     */
    isValidPhone(phone) {
        const phoneRegex = /^\(\d{2}\)\s\d{4,5}-\d{4}$/;
        return phoneRegex.test(phone);
    }
    
    /**
     * Aplica máscara no campo PIX baseado no tipo selecionado
     * @param {Event} e - Evento de input
     */
    applyPixKeyMask(e) {
        const selectedType = this.pixKeyTypeElement.value;
        let value = e.target.value;
        
        switch (selectedType) {
            case 'phone':
                // Aplica máscara de telefone
                value = value.replace(/\D/g, '');
                if (value.length <= 10) {
                    value = value.replace(/(\d{2})(\d)/, '($1) $2');
                    value = value.replace(/(\d{4})(\d)/, '$1-$2');
                } else {
                    value = value.replace(/(\d{2})(\d)/, '($1) $2');
                    value = value.replace(/(\d{5})(\d)/, '$1-$2');
                }
                e.target.value = value;
                break;
            case 'cpf':
                // CPF já é tratado automaticamente
                break;
            case 'email':
                // Email não precisa de máscara
                break;
            case 'random':
                // Chave aleatória não precisa de máscara
                break;
        }
    }
    
    /**
     * Validação em tempo real do campo PIX
     */
    validatePixKeyRealTime() {
        const selectedType = this.pixKeyTypeElement.value;
        const value = this.pixKeyElement.value;
        
        if (!value) {
            this.pixKeyElement.classList.remove('border-red-500', 'border-green-500');
            return;
        }
        
        let isValid = false;
        
        switch (selectedType) {
            case 'cpf':
                isValid = this.isValidCPF(value);
                break;
            case 'email':
                isValid = this.isValidEmail(value);
                break;
            case 'phone':
                isValid = this.isValidPhone(value);
                break;
            case 'random':
                // Para chave aleatória, apenas verifica se não está vazio
                isValid = value.length > 0;
                break;
            default:
                isValid = value.length > 0;
        }
        
        if (isValid) {
            this.pixKeyElement.classList.remove('border-red-500');
            this.pixKeyElement.classList.add('border-green-500');
        } else {
            this.pixKeyElement.classList.remove('border-green-500');
            this.pixKeyElement.classList.add('border-red-500');
        }
    }
}

// Função de conveniência para inicialização rápida
window.initPixFields = function(options = {}) {
    return new PixFieldsHandler(options);
};

// Auto-inicialização se não houver configurações customizadas
document.addEventListener('DOMContentLoaded', function() {
    // Verifica se os elementos padrão existem
    const pixKeyType = document.querySelector('#pix_key_type');
    const pixKey = document.querySelector('#pix_key');
    
    if (pixKeyType && pixKey) {
        // Inicializa automaticamente apenas se não foi inicializado manualmente
        if (!window.pixFieldsHandler) {
            window.pixFieldsHandler = new PixFieldsHandler();
        }
    }
});