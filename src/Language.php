<?php

namespace CH;

use CH\Config;

class Language
{
    private $config;
    private $textdomain;
    private $languages_path;

    public function __construct()
    {
        $this->config = new Config();
        $this->textdomain = $this->config->language_name;
        $this->languages_path = dirname(plugin_basename(__DIR__)) . '/languages/';
    }

    /**
     * Initialize translations using modern WordPress approach
     * WordPress 4.6+ automatically loads translations for plugins when available
     */
    public static function init_translations()
    {
        // Set up locale and domain path for WordPress auto-loading
        // This ensures WordPress can find and load translations automatically
        self::setup_translation_environment();
    }

    /**
     * Setup translation environment for WordPress auto-loading
     * This method prepares the environment without using deprecated functions
     */
    private static function setup_translation_environment()
    {
        // Ensure the languages directory exists
        $full_path = plugin_dir_path(__DIR__) . 'languages';
        if (!file_exists($full_path)) {
            wp_mkdir_p($full_path);
        }

        // WordPress will automatically load translations from:
        // 1. WP_LANG_DIR/plugins/{textdomain}-{locale}.mo
        // 2. Plugin languages directory
        // No manual loading needed for modern WordPress versions
    }

    /**
     * Get the current textdomain
     * @return string
     */
    public function get_textdomain()
    {
        return $this->textdomain;
    }

    /**
     * Get the languages directory path
     * @return string
     */
    public function get_languages_path()
    {
        return $this->languages_path;
    }
}
