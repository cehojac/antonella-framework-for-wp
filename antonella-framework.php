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
 * Network: false
 */

defined('ABSPATH') or die(exit());

$loader = require __DIR__ . '/vendor/autoload.php';
$class = __NAMESPACE__ . '\Start';
$antonella = new $class;
$antonella = new Start;


