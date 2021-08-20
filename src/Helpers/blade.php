<?php
namespace  Antonella\CH\Controllers;

if (class_exists('\Jenssegers\Blade\Blade')&&!function_exists(__NAMESPACE__.'\view')) {
    function view($BladePage, $Attributes)
    {
        $blade = new \Jenssegers\Blade\Blade(plugin_dir_path(dirname(dirname(__FILE__))).'resources/views', plugin_dir_path(dirname(dirname(__FILE__))).'storage/cache');
        return $blade->render($BladePage, $Attributes);
    }
}
