<?php

namespace CH\Admin;

use CH\Config;
use CH\Security;

class Admin
{
    var $plugin_prefix;
    var $plugin_menu;

    public function __construct()
    {
        // Load from new config repository (with legacy fallback)
        $this->plugin_prefix = Config::get('app.plugin_prefix', 'antonella');
        $this->plugin_menu   = Config::get('admin.menu', []);
    }
    /*
     * Admin Menu Page
     *
     */
    public static function menu()
    {
        $admin = new Admin();
        $admin->menu_generator($admin->plugin_menu);
    }

    /**
     * Backend Menu Generator
     * @param array Config::config_menu
     * @ver 1.0
     */
    public function menu_generator($params)
    {
        foreach ($params as $param) {
            if ($param['path'][0] == 'page') {
                $icon = $param['icon'] ? $param['icon'] : 'antonella-icon.png';
                add_menu_page($param['name'], $param['name'], 'manage_options', $param['slug'], $param['function'], plugins_url('../../assets/img/' . $icon, __FILE__));
                if (isset($param['subpages'])) {
                    foreach ($param['subpages'] as $subpage) {
                        add_submenu_page(
                            $param['slug'],
                            $subpage['name'],
                            $subpage['name'],
                            'manage_options',
                            $subpage['slug'],
                            $subpage['function']
                        );
                    }
                }
            } elseif ($param['path'][0] == 'subpage') {
                add_submenu_page(
                    $param['path'][1],
                    $param['name'],
                    $param['name'],
                    'manage_options',
                    $param['slug'],
                    $param['function']
                );
            } elseif ($param['path'][0] == 'option') {
                add_options_page($param['name'], $param['name'], 'manage_options', $param['slug'], $param['function']);
            }
        }
    }

    public function option_page()
    {
        // Check user capabilities
        Security::check_user_capability('manage_options');

        return ('Hello World !!');
    }
}
