/**
 * Sistema de Gestão de Chamados
 * JavaScript - Interações e funcionalidades
 */

document.addEventListener('DOMContentLoaded', function() {
    // Inicializar componentes
    initAlerts();
    initFilters();
    initFormValidation();
    initAnimations();
});

/**
 * Auto-hide alerts após alguns segundos
 */
function initAlerts() {
    const alerts = document.querySelectorAll('.alert');
    
    alerts.forEach(alert => {
        // Adiciona botão de fechar
        const closeBtn = document.createElement('span');
        closeBtn.innerHTML = '×';
        closeBtn.style.cssText = `
            margin-left: auto;
            cursor: pointer;
            font-size: 20px;
            font-weight: bold;
            opacity: 0.7;
        `;
        closeBtn.onclick = () => {
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';
            setTimeout(() => alert.remove(), 300);
        };
        alert.appendChild(closeBtn);
        
        // Auto-hide após 5 segundos
        setTimeout(() => {
            if (alert.parentNode) {
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-10px)';
                setTimeout(() => alert.remove(), 300);
            }
        }, 5000);
    });
}

/**
 * Filtros dinâmicos no dashboard
 */
function initFilters() {
    const statusFilter = document.querySelector('.filter-select[name="status"]');
    const searchInput = document.querySelector('.filter-input[name="busca"]');
    
    // Submit form ao mudar status
    if (statusFilter) {
        statusFilter.addEventListener('change', function() {
            this.form.submit();
        });
    }
    
    // Debounce na busca (pesquisa ao parar de digitar)
    let searchTimeout;
    if (searchInput) {
        searchInput.addEventListener('keyup', function(e) {
            // Se pressionar Enter, submete imediatamente
            if (e.key === 'Enter') {
                this.form.submit();
                return;
            }
            
            // Debounce de 800ms
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                // Só submete se tiver pelo menos 3 caracteres ou estiver vazio
                if (this.value.length >= 3 || this.value.length === 0) {
                    this.form.submit();
                }
            }, 800);
        });
    }
}

/**
 * Validação de formulários
 */
function initFormValidation() {
    const forms = document.querySelectorAll('.auth-form, .chamado-form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Valida campos required
            const requiredFields = form.querySelectorAll('[required]');
            requiredFields.forEach(field => {
                removeError(field);
                
                if (!field.value.trim()) {
                    showError(field, 'Este campo é obrigatório');
                    isValid = false;
                }
            });
            
            // Valida email
            const emailField = form.querySelector('input[type="email"]');
            if (emailField && emailField.value) {
                if (!isValidEmail(emailField.value)) {
                    showError(emailField, 'Email inválido');
                    isValid = false;
                }
            }
            
            // Valida confirmação de senha
            const senha = form.querySelector('#senha');
            const confirmarSenha = form.querySelector('#confirmar_senha');
            if (senha && confirmarSenha && confirmarSenha.value) {
                if (senha.value !== confirmarSenha.value) {
                    showError(confirmarSenha, 'As senhas não coincidem');
                    isValid = false;
                }
            }
            
            // Valida tamanho mínimo da senha
            if (senha && senha.value && senha.value.length < 6) {
                showError(senha, 'A senha deve ter pelo menos 6 caracteres');
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
        
        // Remove erro ao digitar
        form.querySelectorAll('input, textarea, select').forEach(field => {
            field.addEventListener('input', () => removeError(field));
        });
    });
}

/**
 * Mostra erro em um campo
 */
function showError(field, message) {
    field.style.borderColor = '#ef4444';
    
    const errorDiv = document.createElement('div');
    errorDiv.className = 'field-error';
    errorDiv.style.cssText = `
        color: #ef4444;
        font-size: 12px;
        margin-top: 4px;
    `;
    errorDiv.textContent = message;
    
    field.parentNode.appendChild(errorDiv);
}

/**
 * Remove erro de um campo
 */
function removeError(field) {
    field.style.borderColor = '';
    const error = field.parentNode.querySelector('.field-error');
    if (error) error.remove();
}

/**
 * Valida email
 */
function isValidEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

/**
 * Animações e efeitos visuais
 */
function initAnimations() {
    // Animação de entrada para cards
    const cards = document.querySelectorAll('.chamado-card, .stat-card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'all 0.3s ease';
        
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 50);
    });
    
    // Efeito de ripple nos botões
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(button => {
        button.addEventListener('click', function(e) {
            const ripple = document.createElement('span');
            const rect = this.getBoundingClientRect();
            
            ripple.style.cssText = `
                position: absolute;
                background: rgba(255, 255, 255, 0.3);
                border-radius: 50%;
                width: 100px;
                height: 100px;
                left: ${e.clientX - rect.left - 50}px;
                top: ${e.clientY - rect.top - 50}px;
                transform: scale(0);
                animation: ripple 0.6s ease-out;
                pointer-events: none;
            `;
            
            this.style.position = 'relative';
            this.style.overflow = 'hidden';
            this.appendChild(ripple);
            
            setTimeout(() => ripple.remove(), 600);
        });
    });
    
    // Adiciona keyframe de animação
    if (!document.querySelector('#ripple-style')) {
        const style = document.createElement('style');
        style.id = 'ripple-style';
        style.textContent = `
            @keyframes ripple {
                to {
                    transform: scale(4);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    }
}

/**
 * Confirmação de exclusão customizada
 */
function confirmarExclusao(event, titulo) {
    const mensagem = titulo 
        ? `Tem certeza que deseja excluir "${titulo}"?`
        : 'Tem certeza que deseja excluir este item?';
    
    if (!confirm(mensagem)) {
        event.preventDefault();
        return false;
    }
    return true;
}

/**
 * Atualiza status via AJAX (opcional - pode ser implementado futuramente)
 */
function atualizarStatus(chamadoId, novoStatus) {
    // Placeholder para implementação futura de AJAX
    console.log(`Atualizando chamado ${chamadoId} para status ${novoStatus}`);
}

/**
 * Formata data para exibição
 */
function formatarData(dataString) {
    const data = new Date(dataString);
    return data.toLocaleDateString('pt-BR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

/**
 * Contador de caracteres para textarea
 */
function initCharCounter(textareaId, maxLength) {
    const textarea = document.getElementById(textareaId);
    if (!textarea) return;
    
    const counter = document.createElement('div');
    counter.style.cssText = `
        font-size: 12px;
        color: #64748b;
        text-align: right;
        margin-top: 4px;
    `;
    textarea.parentNode.appendChild(counter);
    
    const updateCounter = () => {
        const remaining = maxLength - textarea.value.length;
        counter.textContent = `${textarea.value.length}/${maxLength}`;
        counter.style.color = remaining < 50 ? '#ef4444' : '#64748b';
    };
    
    textarea.addEventListener('input', updateCounter);
    updateCounter();
}
