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
        // Load Gutenberg blocks from configuration (with legacy fallback)
        $blocks = Config::get('gutenberg.blocks', []);
        
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
            // Enqueue the block script after registering
            \wp_enqueue_script($block);
        }
    }
}
