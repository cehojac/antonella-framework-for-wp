<?php

namespace CH;

class Init
{
    public function __construct()
    {
    }

    public static function index()
    {
    }

    public static function addStylesAndScripts()
    {
        if (\file_exists(plugin_dir_path(dirname(__FILE__))).'assets/js/app.js') {
            \wp_enqueue_script(__NAMESPACE__.'-script', \plugin_dir_url(dirname(__FILE__)).'assets/js/app.js');
        }
        if (\file_exists(plugin_dir_path(dirname(__FILE__))).'assets/css/app.css') {
            \wp_register_style(__NAMESPACE__.'-style', \plugin_dir_url(dirname(__FILE__)).'assets/css/app.css');
            \wp_enqueue_style(__NAMESPACE__.'-style');
        }
    }
}
