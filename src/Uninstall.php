<?php

namespace CH;

class Uninstall
{
    public function __construct()
    {
    }

    public static function index()
    {

        $config = new Config();
        $uninstall = new Uninstall();
        $uninstall->delete_options($config->plugin_options);
    }

    public function delete_options($options)
    {
        foreach ($options as $key => $option) {
            if (get_option($key) != false) {
                delete_option($key);
            }
        }
    }
}
