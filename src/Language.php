<?php

namespace CH;

use CH\Config;

class Language
{
    public function __construct()
    {
        $config = new Config();
        load_plugin_textdomain($config->language_name, false, dirname(plugin_basename(__DIR__)) . '/languages/');
    }
}
