<?php
namespace CH;
use CH\Config;

class Gutenberg
{
    public function __construct()
    {

    }

    public static function index()
    {

    }

    public static function blocks()
    {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
        $config = new Config;
        $blocks = $config->gutenberg_blocks;
        
        foreach($blocks as $block)
        {
            \wp_register_script(
                $block,
                \plugin_dir_url(__DIR__) .'assets/js/'. $block.'.js',
                ['wp-blocks', 'wp-i18n', 'wp-element', 'wp-components', 'wp-editor' ],
                null,
                true
            );
            \wp_enqueue_style(
                $block.'-css',
                \plugin_dir_url(__DIR__) .'assets/css/'. $block.'.css',
                [ 'wp-edit-blocks' ],
                null, 
                true
            );
        }
        \wp_enqueue_script($blocks);
    }
}
