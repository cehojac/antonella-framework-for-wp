<?php

namespace Antonella\CH;

/*
Plugin Name: Antonella Framework
Plugin URI:
Description:Another plugin developed on Antonella Framework for WP
Version: 1.8
Author: Carlos Herrera
Author URI:
Framework: Antonella Framework for WP
Framework URI: http://antonellaframework.com
License: GPL2+
Text Domain: Carlos Herrera
Domain Path: /languages
*/

defined('ABSPATH') or die(__('Lo siento por aqui no puedes pasar :)'));

/*
* Class Caller.
* cuando una clase es llamada hace un include
* al archivo con su mismo nombre
* se respeta mayusculas y minusculas
*
* @return null
*/
define('NELLA_URL', __FILE__);

$loader = require __DIR__.'/vendor/autoload.php';
$antonella = new Start();