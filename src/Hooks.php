<?php

/**
 * No modify this file !!!
 */

namespace CH;

use CH\Config;

class Hooks
{
    /**
     * Initialize hooks system and register all WordPress integrations
     */
    public function __construct()
    {
        $this->registrer();
        // Load filters and actions from new config repository (with legacy fallback)
        $filters = Config::get('hooks.add_filter', []);
        $actions = Config::get('hooks.add_action', []);
        $this->filter($filters);
        $this->action($actions);
    }

    /**
     * Register plugin lifecycle hooks (activation, deactivation, uninstall)
     * @see Documentation: docs/configuration/hooks-filters.md for details
     */
    public function registrer()
    {
        $plugin_file = dirname(__DIR__) . '/antonella-framework.php';
        register_activation_hook($plugin_file, array(__NAMESPACE__ . '\Install', 'index'));
        register_deactivation_hook($plugin_file, array(__NAMESPACE__ . '\Desactivate', 'index'));
        register_uninstall_hook($plugin_file, __NAMESPACE__ . '\Uninstall::index');
    }
    /**
     * Register WordPress filters from configuration
     * @see Documentation: docs/configuration/hooks-filters.md for examples
     * @param array $filter Array of filter configurations from Config.php
     */
    public function filter($filter)
    {
        if (isset($filter)) {
            foreach ($filter as $data) {
                call_user_func_array(
                    'add_filter',
                    [
                        isset($data[0]) ? $data[0] : null,
                        isset($data[1]) ? $data[1] : null,
                        isset($data[2]) ? $data[2] : null,
                        isset($data[3]) ? $data[3] : null,
                    ]
                );
            }
        }
    }
    /**
     * Register WordPress actions (framework core + configuration)
     * @see Documentation: docs/configuration/hooks-filters.md for examples
     * @param array $action Array of action configurations from Config.php
     */
    public function action($action)
    {
        // Register framework core actions
        $this->register_core_actions();

        // Register dynamic actions from configuration
        $this->register_dynamic_actions($action);
    }

    /**
     * Register core framework actions
     */
    private function register_core_actions()
    {
        // Admin functionality
        \add_action('admin_menu', array(__NAMESPACE__ . '\Admin\Admin', 'menu'));
        \add_action('wp_dashboard_setup', array(__NAMESPACE__ . '\Admin\Dashboard', 'index'));

        // Core initialization
        \add_action('init', array(__NAMESPACE__ . '\Init', 'index'), 0);

        // Translations and internationalization
        \add_action('plugins_loaded', array(__NAMESPACE__ . '\Language', 'init_translations'), 10);

        // API and REST endpoints
        \add_action('rest_api_init', array(__NAMESPACE__ . '\Api', 'index'), 1);

        // Content types and features
        \add_action('init', array(__NAMESPACE__ . '\Shortcodes', 'index'), 1);
        \add_action('init', array(__NAMESPACE__ . '\PostTypes', 'index'), 1);
        \add_action('widgets_init', array(__NAMESPACE__ . '\Widgets', 'index'), 1);

        // Gutenberg blocks
        \add_action('enqueue_block_editor_assets', array(__NAMESPACE__ . '\Gutenberg', 'blocks'), 1, 10);
    }

    /**
     * Register dynamic actions from configuration
     * @param array $action Array of action configurations
     */
    private function register_dynamic_actions($action)
    {
        if ($action) {
            foreach ($action as $data) {
                if (isset($data)) {
                    call_user_func_array(
                        'add_action',
                        [
                            isset($data[0]) ? $data[0] : null,
                            isset($data[1]) ? $data[1] : null,
                            isset($data[2]) ? $data[2] : null,
                            isset($data[3]) ? $data[3] : null,
                        ]
                    );
                }
            }
        }
    }
}
