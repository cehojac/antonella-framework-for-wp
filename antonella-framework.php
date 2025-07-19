<?php
namespace CH;
/*
Plugin Name: Antonella Framework
Plugin URI: https://antonellaframework.com
Description: Framework for develop WordPress plugins based on Model View Controller
Version: 1.9.0
Author: Carlos Herrera
Author URI: https://carlos-herrera.com
License: GPL2+
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: antonella
Domain Path: /languages
Requires at least: 5.0
Tested up to: 6.4
Requires PHP: 8.0
Network: false
*/

defined( 'ABSPATH' ) or die( exit() );

/*
* Class Caller.
* cuando una clase es llamada hace un include
* al archivo con su mismo nombre
* se respeta mayusculas y minusculas
*
* @return null
*/
$loader = require __DIR__ . '/vendor/autoload.php';
$antonella= new Start;


