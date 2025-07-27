<?php

namespace Config;

use CodeIgniter\Events\Events;
use CodeIgniter\Exceptions\FrameworkException;
use CodeIgniter\HotReloader\HotReloader;

/*
 * --------------------------------------------------------------------
 * Application Events
 * --------------------------------------------------------------------
 * Events allow you to tap into the execution of the program without
 * modifying or extending core files. This file provides a central
 * location to define your events, though they can always be added
 * at run-time, also, if needed.
 *
 * You create code that can execute by subscribing to events with
 * the 'on()' method. This accepts any form of callable, including
 * Closures, that will be executed when the event is triggered.
 *
 * Example:
 *      Events::on('create', [$myInstance, 'myMethod']);
 */

Events::on('pre_system', static function (): void {
    if (ENVIRONMENT !== 'testing') {
        if (ini_get('zlib.output_compression')) {
            throw FrameworkException::forEnabledZlibOutputCompression();
        }

        while (ob_get_level() > 0) {
            ob_end_flush();
        }

        ob_start(static fn ($buffer) => $buffer);
    }

    /*
     * --------------------------------------------------------------------
     * Debug Toolbar Listeners.
     * --------------------------------------------------------------------
     * If you delete, they will no longer be collected.
     */
    if (CI_DEBUG && ! is_cli()) {
        Events::on('DBQuery', 'CodeIgniter\Debug\Toolbar\Collectors\Database::collect');
        service('toolbar')->respond();
        // Hot Reload route - for framework use on the hot reloader.
        if (ENVIRONMENT === 'development') {
            service('routes')->get('__hot-reload', static function (): void {
                (new HotReloader())->run();
            });
        }
    }
});

/*
 * --------------------------------------------------------------------
 * Load Timezone from Database
 * --------------------------------------------------------------------
 * This event loads the timezone configuration from the database
 * and sets it as the default timezone for the application.
 */
Events::on('pre_system', static function (): void {
    try {
        // Verificar se as tabelas existem antes de tentar carregar
        $db = \Config\Database::connect();
        
        // Verificar se a tabela settings existe
        if ($db->tableExists('settings')) {
            // Buscar o timezone configurado no banco
            $query = $db->query(
                "SELECT value FROM settings WHERE category = 'system' AND setting_key = 'timezone' LIMIT 1"
            );
            
            $result = $query->getRow();
            
            if ($result && !empty($result->value)) {
                // Validar se o timezone é válido
                if (in_array($result->value, timezone_identifiers_list())) {
                    date_default_timezone_set($result->value);
                }
            }
        }
    } catch (\Exception $e) {
        // Em caso de erro, usar o timezone padrão definido no App.php
        // Não fazer nada para não quebrar a aplicação
        log_message('error', 'Erro ao carregar timezone do banco: ' . $e->getMessage());
    }
});
