<?php
namespace CH;
/**
 * Plugin Name: Antonella Framework
 * Plugin URI: https://antonellaframework.com
 * Description: Framework for developing WordPress plugins based on Model View Controller.
 * Version: 1.9.0
 * Author: Carlos Herrera
 * Author URI: https://carlos-herrera.com
 * License: GPL2+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: antonella-framework
 * Domain Path: /languages
 * Requires at least: 5.0
 * Tested up to: 6.4
 * Requires PHP: 8.0
 */

defined('ABSPATH') or die(exit());

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    $loader = require __DIR__ . '/vendor/autoload.php';
    $antonella = new Start;
} else {
    add_action('admin_notices', function() {
        echo '<div class="notice notice-error"><p><strong>Antonella Framework:</strong> Dependencies missing. Please run <code>composer install</code> in the plugin directory.</p></div>';
    });
    return;
}


