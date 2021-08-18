<?php

namespace  Antonella\CH;

if (!function_exists(__NAMESPACE__.'\asset')) {
    /**
     * Show the assets folder url
     * for call this function globally:
     * asset();
     */
    function asset($uri =""){
        return plugin_dir_url( dirname(dirname(__FILE__)))."assets/$uri";
    } 
}
if (!function_exists(__NAMESPACE__.'\path')) {
    /**
     * Show the plugin path
     * for call this function globally:
     * path();
     */
    function path($path =""){
        return plugin_dir_path( dirname(dirname(__FILE__))).$path;
    } 
}
?>