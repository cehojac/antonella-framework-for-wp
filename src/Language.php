<?php
namespace CH;

class Language
{
    public function __construct()
    {
        $config= new Config();
        load_plugin_textdomain($config->language_name, false, basename(basename( dirname( __FILE__ ) )) . '/languages' );
    }
}
