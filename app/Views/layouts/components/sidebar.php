<!-- Sidebar -->
<aside id="sidebar" class="sidebar-expanded fixed top-0 left-0 z-30 h-screen transition-all duration-300 bg-gray-800 border-r border-gray-700" style="max-width: 16rem;">
    <div class="h-full px-3 py-4 overflow-y-auto bg-gray-800">
        <!-- Logo -->
        <div class="flex items-center justify-center mb-6">
            <div class="flex items-center">
                <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="ml-2 text-xl font-semibold text-white sidebar-text">AMX Cred</span>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="space-y-2">
            <?php
            $currentUrl = current_url();
            ?>

            <!-- Dashboard -->
            <a href="<?= base_url('/') ?>"
               class="flex items-center p-2 text-gray-300 rounded-lg hover:bg-gray-700 group <?= $currentUrl === base_url('/') ? 'bg-blue-600 text-white' : '' ?>">
                <svg class="w-5 h-5 text-gray-400 transition duration-75 group-hover:text-white <?= $currentUrl === base_url('/') ? 'text-white' : '' ?>" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                    <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                </svg>
                <span class="ml-3 sidebar-text">Dashboard</span>
            </a>

            <!-- Clients Module -->
            <div class="space-y-1">
                <button type="button"
                        class="flex items-center w-full p-2 text-base text-gray-300 transition duration-75 rounded-lg group hover:bg-gray-700"
                        aria-controls="dropdown-clients"
                        data-collapse-toggle="dropdown-clients">
                    <svg class="w-5 h-5 text-gray-400 transition duration-75 group-hover:text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="flex-1 ml-3 text-left whitespace-nowrap sidebar-text">Clientes</span>
                    <svg class="w-3 h-3 text-gray-400 sidebar-text" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6" style="transition: transform 0.3s ease;">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                    </svg>
                </button>
                
                <ul id="dropdown-clients" class="py-2 space-y-1 dropdown-hidden">
                    <li>
                        <a href="<?= base_url('/clients') ?>"
                           class="flex items-center w-full p-2 text-gray-300 transition duration-75 rounded-lg pl-11 group hover:bg-gray-700 <?= strpos($currentUrl, '/clients') !== false ? 'bg-blue-600 text-white' : '' ?>">
                            <span class="sidebar-text">Listar Clientes</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('/clients/create') ?>"
                           class="flex items-center w-full p-2 text-gray-300 transition duration-75 rounded-lg pl-11 group hover:bg-gray-700">
                            <span class="sidebar-text">Novo Cliente</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Settings Module -->
            <div class="space-y-1">
                <button type="button"
                        class="flex items-center w-full p-2 text-base text-gray-300 transition duration-75 rounded-lg group hover:bg-gray-700"
                        aria-controls="dropdown-settings"
                        data-collapse-toggle="dropdown-settings">
                    <svg class="w-5 h-5 text-gray-400 transition duration-75 group-hover:text-white" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="flex-1 ml-3 text-left whitespace-nowrap sidebar-text">Configurações</span>
                    <svg class="w-3 h-3 text-gray-400 sidebar-text" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6" style="transition: transform 0.3s ease;">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                    </svg>
                </button>
                
                <ul id="dropdown-settings" class="py-2 space-y-1 dropdown-hidden">
                    <li>
                        <a href="<?= base_url('/settings') ?>"
                           class="flex items-center w-full p-2 text-gray-300 transition duration-75 rounded-lg pl-11 group hover:bg-gray-700 <?= $currentUrl === base_url('/settings') ? 'bg-blue-600 text-white' : '' ?>">
                            <span class="sidebar-text">Configurações Gerais</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('/settings/required-fields') ?>"
                           class="flex items-center w-full p-2 text-gray-300 transition duration-75 rounded-lg pl-11 group hover:bg-gray-700 <?= strpos($currentUrl, '/settings/required-fields') !== false ? 'bg-blue-600 text-white' : '' ?>">
                            <span class="sidebar-text">Campos Obrigatórios</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('/settings/cpf-api') ?>"
                           class="flex items-center w-full p-2 text-gray-300 transition duration-75 rounded-lg pl-11 group hover:bg-gray-700 <?= strpos($currentUrl, '/settings/cpf-api') !== false ? 'bg-blue-600 text-white' : '' ?>">
                            <span class="sidebar-text">API CPF</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('/settings/smtp') ?>"
                           class="flex items-center w-full p-2 text-gray-300 transition duration-75 rounded-lg pl-11 group hover:bg-gray-700 <?= strpos($currentUrl, '/settings/smtp') !== false ? 'bg-blue-600 text-white' : '' ?>">
                            <span class="sidebar-text">SMTP</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('/settings/colors') ?>"
                           class="flex items-center w-full p-2 text-gray-300 transition duration-75 rounded-lg pl-11 group hover:bg-gray-700 <?= strpos($currentUrl, '/settings/colors') !== false ? 'bg-blue-600 text-white' : '' ?>">
                            <span class="sidebar-text">Cores do Sistema</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('/settings/payment') ?>"
                           class="flex items-center w-full p-2 text-gray-300 transition duration-75 rounded-lg pl-11 group hover:bg-gray-700 <?= strpos($currentUrl, '/settings/payment') !== false ? 'bg-blue-600 text-white' : '' ?>">
                            <span class="sidebar-text">Pagamentos</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('/settings/loan-plans') ?>"
                           class="flex items-center w-full p-2 text-gray-300 transition duration-75 rounded-lg pl-11 group hover:bg-gray-700 <?= strpos($currentUrl, '/settings/loan-plans') !== false ? 'bg-blue-600 text-white' : '' ?>">
                            <span class="sidebar-text">Planos de Empréstimo</span>
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Future Modules will be added here -->
            <!-- The structure is prepared for easy expansion -->
        </nav>

        <!-- Footer -->
        <div class="absolute bottom-0 left-0 justify-center p-4 space-x-4 w-full lg:flex bg-gray-900 border-t border-gray-700">
            <a href="#" class="inline-flex justify-center p-2 text-gray-400 rounded cursor-pointer hover:text-white hover:bg-gray-700">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"></path>
                </svg>
            </a>
            <a href="#" class="inline-flex justify-center p-2 text-gray-400 rounded cursor-pointer hover:text-white hover:bg-gray-700">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z"></path>
                </svg>
            </a>
        </div>
    </div>
</aside>