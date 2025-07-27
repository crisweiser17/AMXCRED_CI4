<?= $this->extend('layouts/main_layout') ?>

<?= $this->section('content') ?>
<div class="max-w-7xl mx-auto">
    <!-- Page Header -->
    <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Lista de Clientes</h1>
                <p class="mt-1 text-sm text-gray-600">Gerencie todos os clientes cadastrados no sistema</p>
            </div>
            <a href="<?= base_url('/clients/create') ?>" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Novo Cliente
            </a>
        </div>
    </div>

    <!-- Filtros e Busca -->
    <div class="bg-white shadow-sm rounded-lg mb-6">
        <div class="p-6">
            <form id="filters-form" class="space-y-4">
                <!-- Linha 1: Busca e Botões -->
                <div class="flex flex-col sm:flex-row gap-4 items-end">
                    <div class="flex-1">
                        <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Buscar</label>
                        <div class="relative">
                            <input type="text" 
                                   id="search" 
                                   name="search" 
                                   value="<?= esc($filters['search'] ?? '') ?>"
                                   placeholder="Nome, CPF, email ou telefone..."
                                   class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="flex gap-2 ml-auto">
                        <button type="button" id="clear-filters" class="px-6 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Limpar
                        </button>
                        <button type="submit" class="px-6 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Buscar
                        </button>
                    </div>
                </div>
                
                <!-- Linha 2: Filtros -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                    <!-- Filtro de Elegibilidade -->
                    <div>
                        <label for="eligibility" class="block text-sm font-medium text-gray-700 mb-2">Elegibilidade</label>
                        <select id="eligibility" name="eligibility" class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 h-10">
                            <option value="all" <?= ($filters['eligibility'] ?? '') === 'all' ? 'selected' : '' ?>>Todos</option>
                            <option value="eligible" <?= ($filters['eligibility'] ?? '') === 'eligible' ? 'selected' : '' ?>>Elegíveis</option>
                            <option value="not_eligible" <?= ($filters['eligibility'] ?? '') === 'not_eligible' ? 'selected' : '' ?>>Não Elegíveis</option>
                        </select>
                    </div>
                    
                    <!-- Data Início -->
                    <div>
                        <label for="date_from" class="block text-sm font-medium text-gray-700 mb-2">Data Início</label>
                        <input type="date" 
                               id="date_from" 
                               name="date_from" 
                               value="<?= esc($filters['date_from'] ?? '') ?>"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 h-10">
                    </div>
                    
                    <!-- Data Fim -->
                    <div>
                        <label for="date_to" class="block text-sm font-medium text-gray-700 mb-2">Data Fim</label>
                        <input type="date" 
                               id="date_to" 
                               name="date_to" 
                               value="<?= esc($filters['date_to'] ?? '') ?>"
                               class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 h-10">
                    </div>
                    
                    <!-- Ordenação -->
                    <div>
                        <label for="order_by" class="block text-sm font-medium text-gray-700 mb-2">Ordenar por</label>
                        <div class="flex gap-2 h-10">
                            <select id="order_by" name="order_by" class="flex-1 px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 h-full">
                                <option value="full_name" <?= ($filters['order_by'] ?? '') === 'full_name' ? 'selected' : '' ?>>Nome</option>
                                <option value="cpf" <?= ($filters['order_by'] ?? '') === 'cpf' ? 'selected' : '' ?>>CPF</option>
                                <option value="created_at" <?= ($filters['order_by'] ?? '') === 'created_at' ? 'selected' : '' ?>>Data Cadastro</option>
                                <option value="is_eligible" <?= ($filters['order_by'] ?? '') === 'is_eligible' ? 'selected' : '' ?>>Elegibilidade</option>
                            </select>
                            <select id="order_dir" name="order_dir" class="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 h-full">
                                <option value="asc" <?= ($filters['order_dir'] ?? '') === 'asc' ? 'selected' : '' ?>>↑</option>
                                <option value="desc" <?= ($filters['order_dir'] ?? '') === 'desc' ? 'selected' : '' ?>>↓</option>
                            </select>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Resultados -->
    <div class="bg-white shadow-sm rounded-lg">
        <div class="p-6">
            <!-- Contador de Resultados -->
            <div class="mb-4 flex justify-between items-center">
                <div id="results-count" class="text-sm text-gray-600">
                    <?php if (isset($pagination)): ?>
                        Mostrando <?= (($pagination['current_page'] - 1) * $pagination['per_page']) + 1 ?> até 
                        <?= min($pagination['current_page'] * $pagination['per_page'], $pagination['total_records']) ?> de 
                        <?= $pagination['total_records'] ?> resultados
                    <?php endif; ?>
                </div>
                <div id="loading-indicator" class="hidden">
                    <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </div>
            
            <!-- Tabela de Resultados -->
            <div id="clients-table">
            <?php if (empty($clients)): ?>
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Nenhum cliente cadastrado</h3>
                    <p class="mt-1 text-sm text-gray-500">Comece adicionando um novo cliente ao sistema.</p>
                    <div class="mt-6">
                        <a href="<?= base_url('/clients/create') ?>" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Adicionar Cliente
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    ID
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nome
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    CPF
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Telefone
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Data Nascimento
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Data Cadastro
                                </th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Elegível
                                </th>
                                <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Ações
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($clients as $client): ?>
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        <?= str_pad($client['id'], 3, '0', STR_PAD_LEFT) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?= esc($client['full_name']) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= esc($client['cpf']) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= esc($client['phone']) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= date('d/m/Y', strtotime($client['birth_date'])) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= date('d/m/Y H:i', strtotime($client['created_at'])) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <?php if (isset($client['is_eligible']) && $client['is_eligible']): ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                                Sim
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                </svg>
                                                Não
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <div class="flex items-center justify-end space-x-2">
                                            <a href="<?= base_url('/clients/view/' . $client['id']) ?>" class="text-blue-600 hover:text-blue-900" title="Visualizar cliente">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </a>
                                            <a href="<?= base_url('/clients/edit/' . $client['id']) ?>" class="text-blue-600 hover:text-blue-900" title="Editar cliente">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </a>
                                            <a href="<?= base_url('/clients/verify/' . $client['id']) ?>" class="text-orange-600 hover:text-orange-900" title="Verificar cliente">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </a>
                                            <?php if (isset($client['is_eligible']) && $client['is_eligible']): ?>
                                                <a href="<?= base_url('/loans/create?client_id=' . $client['id']) ?>" class="text-green-600 hover:text-green-900" title="Criar empréstimo">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                </a>
                                            <?php endif; ?>
                                            <a href="#" class="text-red-600 hover:text-red-900" title="Excluir cliente">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
            </div>
            
            <!-- Paginação -->
            <?php if (isset($pagination) && $pagination['total_pages'] > 1): ?>
                <div class="mt-6">
                    <nav class="flex items-center justify-between px-4 py-3 bg-white border-t border-gray-200 sm:px-6">
                        <div class="flex justify-between flex-1 sm:hidden">
                            <?php if ($pagination['has_previous']): ?>
                                <button type="button" class="pagination-btn relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50" data-page="<?= $pagination['current_page'] - 1 ?>">Anterior</button>
                            <?php else: ?>
                                <span class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-400 bg-gray-100 border border-gray-300 rounded-md cursor-not-allowed">Anterior</span>
                            <?php endif; ?>
                            
                            <?php if ($pagination['has_next']): ?>
                                <button type="button" class="pagination-btn relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50" data-page="<?= $pagination['current_page'] + 1 ?>">Próximo</button>
                            <?php else: ?>
                                <span class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-400 bg-gray-100 border border-gray-300 rounded-md cursor-not-allowed">Próximo</span>
                            <?php endif; ?>
                        </div>
                        
                        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700">
                                    Página <span class="font-medium"><?= $pagination['current_page'] ?></span> de <span class="font-medium"><?= $pagination['total_pages'] ?></span>
                                </p>
                            </div>
                            <div>
                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                    <?php if ($pagination['has_previous']): ?>
                                        <button type="button" class="pagination-btn relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50" data-page="<?= $pagination['current_page'] - 1 ?>">
                                            <span class="sr-only">Anterior</span>
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    <?php endif; ?>
                                    
                                    <?php 
                                    $startPage = max(1, $pagination['current_page'] - 2);
                                    $endPage = min($pagination['total_pages'], $pagination['current_page'] + 2);
                                    
                                    for ($i = $startPage; $i <= $endPage; $i++): 
                                    ?>
                                        <?php if ($i == $pagination['current_page']): ?>
                                            <span class="relative inline-flex items-center px-4 py-2 border border-blue-500 bg-blue-50 text-sm font-medium text-blue-600">
                                                <?= $i ?>
                                            </span>
                                        <?php else: ?>
                                            <button type="button" class="pagination-btn relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50" data-page="<?= $i ?>">
                                                <?= $i ?>
                                            </button>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                    
                                    <?php if ($pagination['has_next']): ?>
                                        <button type="button" class="pagination-btn relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50" data-page="<?= $pagination['current_page'] + 1 ?>">
                                            <span class="sr-only">Próximo</span>
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    <?php endif; ?>
                                </nav>
                            </div>
                        </div>
                    </nav>
                </div>
            <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchForm = document.getElementById('filters-form');
    const searchInput = document.getElementById('search');
    const eligibilityFilter = document.getElementById('eligibility');
    const startDateFilter = document.getElementById('date_from');
    const endDateFilter = document.getElementById('date_to');
    const sortFilter = document.getElementById('order_by');
    const clearFiltersBtn = document.getElementById('clear-filters');
    const loadingIndicator = document.getElementById('loading-indicator');
    const resultsContainer = document.getElementById('clients-table');
    const resultsCount = document.getElementById('results-count');
    
    let searchTimeout;
    
    // Função para realizar busca (submete o formulário)
    function performSearch() {
        clearTimeout(searchTimeout);
        
        searchTimeout = setTimeout(() => {
            if (searchForm) {
                searchForm.submit();
            }
        }, 300);
    }
    
    // Event listeners para os filtros
    if (searchInput) {
        searchInput.addEventListener('input', performSearch);
    }
    
    if (eligibilityFilter) {
        eligibilityFilter.addEventListener('change', performSearch);
    }
    
    if (startDateFilter) {
        startDateFilter.addEventListener('change', performSearch);
    }
    
    if (endDateFilter) {
        endDateFilter.addEventListener('change', performSearch);
    }
    
    if (sortFilter) {
        sortFilter.addEventListener('change', performSearch);
    }
    
    // Limpar filtros
    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', function() {
            searchForm.reset();
            performSearch();
        });
    }
    
    // Carregar filtros da URL na inicialização
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('search')) {
        searchInput.value = urlParams.get('search');
    }
    if (urlParams.has('eligibility')) {
        eligibilityFilter.value = urlParams.get('eligibility');
    }
    if (urlParams.has('start_date')) {
        startDateFilter.value = urlParams.get('start_date');
    }
    if (urlParams.has('end_date')) {
        endDateFilter.value = urlParams.get('end_date');
    }
    if (urlParams.has('sort')) {
        sortFilter.value = urlParams.get('sort');
    }
});
</script>

<?= $this->endSection() ?>