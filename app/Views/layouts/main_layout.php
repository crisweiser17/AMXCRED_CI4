<!DOCTYPE html>
<html lang="pt-BR" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($title) ? $title . ' - AMX Cred' : 'AMX Cred' ?></title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Preline UI - Temporarily disabled -->
    <!-- <link rel="stylesheet" href="https://preline.co/assets/css/main.min.css?v=1.0.0"> -->
    
    <!-- Heroicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/heroicons/2.0.18/24/outline/style.min.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom Styles -->
    <style>
        /* Reset and base styles */
        * {
            box-sizing: border-box;
        }
        
        body {
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
        
        /* Hide any problematic pseudo-elements */
        *::before,
        *::after {
            max-width: 100vw !important;
            max-height: 100vh !important;
        }
        
        .sidebar-collapsed {
            width: 4rem !important;
            overflow: hidden;
        }
        .sidebar-expanded {
            width: 16rem !important;
            max-width: 16rem;
        }
        .content-collapsed {
            margin-left: 4rem !important;
        }
        .content-expanded {
            margin-left: 16rem !important;
        }
        .sidebar-hidden {
            transform: translateX(-100%) !important;
        }
        .sidebar-visible {
            transform: translateX(0) !important;
        }
        .sidebar-text {
            opacity: 1;
            transition: opacity 0.3s ease;
        }
        .sidebar-collapsed .sidebar-text {
            opacity: 0;
            display: none;
        }
        .dropdown-hidden {
            display: none;
        }
        .dropdown-visible {
            display: block;
        }
        
        /* Fix for sidebar z-index and positioning */
        #sidebar {
            z-index: 30;
            max-width: 16rem;
            width: 16rem;
        }
        
        #main-content {
            position: relative;
            z-index: 10;
            min-height: 100vh;
        }
        
        /* Hide any oversized elements */
        .bg-blue-50::before,
        .bg-blue-50::after {
            display: none !important;
        }
        
        @media (max-width: 768px) {
            .sidebar-expanded {
                width: 16rem;
                max-width: 16rem;
            }
            .content-expanded {
                margin-left: 0;
            }
        }
    </style>
</head>
<body class="h-full bg-gray-50">
    <!-- Sidebar -->
    <?= $this->include('layouts/components/sidebar') ?>
    
    <!-- Main Content -->
    <div id="main-content" class="content-expanded transition-all duration-300">
        <!-- Header -->
        <?= $this->include('layouts/components/header') ?>
        
        <!-- Page Content -->
        <main class="p-6">
            <?= $this->renderSection('content') ?>
        </main>
    </div>
    
    <!-- Preline JS - Temporarily disabled -->
    <!-- <script src="https://preline.co/assets/js/hs-ui.bundle.js?v=1.0.0"></script> -->
    
    <!-- Custom JS -->
    <script>
        // Toggle sidebar
        function initSidebarToggle() {
            const toggleButton = document.getElementById('sidebar-toggle');
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            
            if (!toggleButton || !sidebar || !mainContent) {
                console.error('Sidebar elements not found:', {
                    toggleButton: !!toggleButton,
                    sidebar: !!sidebar,
                    mainContent: !!mainContent
                });
                return;
            }
            
            // Store initial state
            let isCollapsed = false;
            
            toggleButton.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Sidebar toggle clicked, current state:', isCollapsed);
                
                if (window.innerWidth < 768) {
                    // Mobile: hide/show completely
                    if (isCollapsed) {
                        sidebar.classList.remove('sidebar-hidden');
                        sidebar.classList.add('sidebar-visible');
                        isCollapsed = false;
                    } else {
                        sidebar.classList.remove('sidebar-visible');
                        sidebar.classList.add('sidebar-hidden');
                        isCollapsed = true;
                    }
                    console.log('Mobile toggle applied, new state:', isCollapsed);
                } else {
                    // Desktop: collapse/expand
                    if (isCollapsed) {
                        // Expand
                        sidebar.classList.remove('sidebar-collapsed');
                        sidebar.classList.add('sidebar-expanded');
                        mainContent.classList.remove('content-collapsed');
                        mainContent.classList.add('content-expanded');
                        isCollapsed = false;
                        console.log('Sidebar expanded');
                    } else {
                        // Collapse
                        sidebar.classList.remove('sidebar-expanded');
                        sidebar.classList.add('sidebar-collapsed');
                        mainContent.classList.remove('content-expanded');
                        mainContent.classList.add('content-collapsed');
                        isCollapsed = true;
                        console.log('Sidebar collapsed');
                    }
                }
            });
        }

        // Toggle dropdown menus
        document.addEventListener('click', function(e) {
            if (e.target.closest('[data-collapse-toggle]')) {
                const button = e.target.closest('[data-collapse-toggle]');
                const targetId = button.getAttribute('aria-controls');
                const dropdown = document.getElementById(targetId);
                const arrow = button.querySelector('svg:last-child');
                
                if (dropdown) {
                    if (dropdown.classList.contains('dropdown-hidden')) {
                        dropdown.classList.remove('dropdown-hidden');
                        dropdown.classList.add('dropdown-visible');
                        arrow.style.transform = 'rotate(180deg)';
                    } else {
                        dropdown.classList.add('dropdown-hidden');
                        dropdown.classList.remove('dropdown-visible');
                        arrow.style.transform = 'rotate(0deg)';
                    }
                }
            }
        });

        // Initialize sidebar state based on screen size
        function initSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            
            if (window.innerWidth < 768) {
                sidebar.classList.add('sidebar-hidden');
                sidebar.classList.remove('sidebar-expanded', 'sidebar-collapsed');
                mainContent.classList.remove('content-collapsed', 'content-expanded');
            } else {
                sidebar.classList.add('sidebar-expanded');
                sidebar.classList.remove('sidebar-hidden', 'sidebar-visible');
                mainContent.classList.add('content-expanded');
            }
        }

        // Initialize dropdowns as hidden
        function initDropdowns() {
            const dropdowns = document.querySelectorAll('[id^="dropdown-"]');
            dropdowns.forEach(dropdown => {
                dropdown.classList.add('dropdown-hidden');
                dropdown.classList.remove('dropdown-visible');
            });
        }

        // Initialize on load and resize
        window.addEventListener('load', function() {
            initSidebar();
            initDropdowns();
            initSidebarToggle();
        });
        window.addEventListener('resize', initSidebar);
        
        // Also initialize when DOM is ready (fallback)
        document.addEventListener('DOMContentLoaded', function() {
            initSidebar();
            initDropdowns();
            initSidebarToggle();
        });
    </script>
</body>
</html>