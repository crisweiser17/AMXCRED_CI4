<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto">
    <!-- Page Header -->
    <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Campos Obrigatórios</h1>
                <p class="mt-1 text-sm text-gray-600">Configure quais campos são obrigatórios no cadastro de clientes</p>
            </div>
            <a href="<?= base_url('/settings') ?>" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Voltar
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    <div id="alert-container" class="mb-6" style="display: none;">
        <div id="alert-message" class="p-4 rounded-md">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg id="alert-icon" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div class="ml-3">
                    <p id="alert-text" class="text-sm font-medium"></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Configuration Form -->
    <form id="required-fields-form" class="space-y-6">
        <?php foreach ($fieldGroups as $groupKey => $group): ?>
            <div class="bg-white shadow-sm rounded-lg p-6">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0">
                        <?php
                        $icons = [
                            'personal' => '<svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>',
                            'professional' => '<svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2-2v2m8 0V6a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V8a2 2 0 012-2V6"></path></svg>',
                            'pix' => '<svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>',
                            'address' => '<svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>',
                            'documents' => '<svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>'
                        ];
                        echo $icons[$groupKey] ?? '';
                        ?>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-medium text-gray-900"><?= esc($group['title']) ?></h3>
                        <p class="text-sm text-gray-500">Configure os campos obrigatórios desta seção</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <?php foreach ($group['fields'] as $field): ?>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <?php if ($field['locked']): ?>
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                        </svg>
                                    <?php else: ?>
                                        <div class="w-4 h-4"></div>
                                    <?php endif; ?>
                                </div>
                                <div class="ml-2">
                                    <label for="field_<?= esc($field['key']) ?>" class="text-sm font-medium text-gray-700">
                                        <?= esc($field['description']) ?>
                                        <?php if ($field['locked']): ?>
                                            <span class="text-xs text-gray-500">(sempre obrigatório)</span>
                                        <?php endif; ?>
                                    </label>
                                </div>
                            </div>
                            <div class="flex-shrink-0">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" 
                                           id="field_<?= esc($field['key']) ?>"
                                           name="<?= esc($field['key']) ?>" 
                                           value="1" 
                                           class="sr-only peer field-toggle" 
                                           <?= $field['required'] ? 'checked' : '' ?>
                                           <?= $field['locked'] ? 'disabled' : '' ?>>
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600 <?= $field['locked'] ? 'opacity-50 cursor-not-allowed' : '' ?>"></div>
                                </label>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>

        <!-- Save Button -->
        <div class="bg-white shadow-sm rounded-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600">
                        <strong>Nota:</strong> Os campos "Nome Completo" e "CPF" são sempre obrigatórios e não podem ser alterados.
                    </p>
                </div>
                <button type="submit" 
                        id="save-button"
                        class="inline-flex items-center px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span id="save-text">Salvar Configurações</span>
                </button>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('required-fields-form');
    const saveButton = document.getElementById('save-button');
    const saveText = document.getElementById('save-text');
    const alertContainer = document.getElementById('alert-container');
    const alertMessage = document.getElementById('alert-message');
    const alertIcon = document.getElementById('alert-icon');
    const alertText = document.getElementById('alert-text');

    function showAlert(message, type = 'success') {
        alertContainer.style.display = 'block';
        
        if (type === 'success') {
            alertMessage.className = 'p-4 rounded-md bg-green-50 border border-green-200';
            alertIcon.className = 'h-5 w-5 text-green-400';
            alertText.className = 'text-sm font-medium text-green-800';
        } else {
            alertMessage.className = 'p-4 rounded-md bg-red-50 border border-red-200';
            alertIcon.className = 'h-5 w-5 text-red-400';
            alertText.className = 'text-sm font-medium text-red-800';
        }
        
        alertText.textContent = message;
        
        // Auto hide after 5 seconds
        setTimeout(() => {
            alertContainer.style.display = 'none';
        }, 5000);
    }

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Disable button and show loading
        saveButton.disabled = true;
        saveText.textContent = 'Salvando...';
        
        // Collect form data
        const formData = {};
        const checkboxes = form.querySelectorAll('.field-toggle');
        
        checkboxes.forEach(checkbox => {
            formData[checkbox.name] = checkbox.checked;
        });
        
        // Send AJAX request
        fetch('<?= base_url('/settings/update-required-fields') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert(data.message, 'success');
            } else {
                showAlert(data.message, 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('Erro ao salvar configurações. Tente novamente.', 'error');
        })
        .finally(() => {
            // Re-enable button
            saveButton.disabled = false;
            saveText.textContent = 'Salvar Configurações';
        });
    });
});
</script>
<?= $this->endSection() ?>