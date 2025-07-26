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
        $config = new Config();
        $blocks = $config->gutenberg_blocks;
        
        foreach ($blocks as $block) {
            \wp_register_script(
                $block,
                \plugin_dir_url(__DIR__) . 'assets/js/' . $block . '.js',
                ['wp-blocks', 'wp-i18n', 'wp-element', 'wp-components', 'wp-editor' ],
                '1.0.0',
                true
            );
            \wp_enqueue_style(
                $block . '-css',
                \plugin_dir_url(__DIR__) . 'assets/css/' . $block . '.css',
                [ 'wp-edit-blocks' ],
                '1.0.0',
                true
            );
        }
        \wp_enqueue_script($blocks);
    }
}
