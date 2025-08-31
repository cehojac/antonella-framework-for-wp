<?php

namespace CH;

class Config
{
    /**
     * Static facade for configuration access.
     * Provides Laravel-like dot-notation reads from /config files without .env or helpers.
     * Backwards-compatible with legacy public properties defined in this class.
     */
    protected static ?ConfigRepository $repository = null;

    /**
     * Get a configuration value using dot-notation, e.g. 'app.language_name'.
     * Falls back to legacy properties when a config file or key is missing.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get(string $key, $default = null)
    {
        $repo = self::repo();
        $value = $repo->get($key, '__MISS__');
        if ($value !== '__MISS__') {
            return $value;
        }
        // Fallback to legacy mapping
        [$file, $path] = self::splitKey($key);
        $legacy = self::legacy($file);
        if ($path === null) {
            return $legacy ?? $default;
        }
        $f = self::dataGet($legacy, $path);
        return $f !== null ? $f : $default;
    }

    /**
     * Determine if a configuration key exists (including legacy fallback).
     */
    public static function has(string $key): bool
    {
        return self::get($key, '__MISS__') !== '__MISS__';
    }

    /**
     * Set a configuration value at runtime (in-memory only).
     */
    public static function set(string $key, $value): void
    {
        self::repo()->set($key, $value);
    }

    /**
     * Return the entire configuration array for a given file.
     * Includes legacy fallback if the file is missing.
     *
     * @return array<string, mixed>
     */
    public static function all(string $file): array
    {
        $repoData = self::repo()->all($file);
        if (!empty($repoData)) {
            return $repoData;
        }
        $legacy = self::legacy($file);
        return is_array($legacy) ? $legacy : [];
    }

    /**
     * WordPress context values (no .env, no wp-config dependency).
     * Example keys: site.url, site.home, site.locale, wp.version, wp.multisite, db.prefix
     */
    public static function context(string $key, $default = null)
    {
        switch ($key) {
            case 'site.url':
                return function_exists('site_url') ? site_url() : $default;
            case 'site.home':
                return function_exists('home_url') ? home_url() : $default;
            case 'site.locale':
                return function_exists('get_locale') ? get_locale() : $default;
            case 'wp.version':
                return function_exists('get_bloginfo') ? get_bloginfo('version') : $default;
            case 'wp.multisite':
                return function_exists('is_multisite') ? is_multisite() : $default;
            case 'db.prefix':
                return isset($GLOBALS['wpdb']->prefix) ? $GLOBALS['wpdb']->prefix : $default;
            default:
                /** Allow projects to provide more via filter if available */
                if (function_exists('apply_filters')) {
                    $val = apply_filters('antonella/config_context', null, $key, $default);
                    return $val !== null ? $val : $default;
                }
                return $default;
        }
    }

    // ---- Internal helpers -------------------------------------------------

    protected static function repo(): ConfigRepository
    {
        if (!self::$repository) {
            self::$repository = new ConfigRepository();
        }
        return self::$repository;
    }

    /** @return array{0:string,1:?string} */
    protected static function splitKey(string $key): array
    {
        $parts = explode('.', $key, 2);
        return [$parts[0], $parts[1] ?? null];
    }

    protected static function dataGet($target, string $path)
    {
        if (!is_array($target)) {
            return null;
        }
        $segments = explode('.', $path);
        foreach ($segments as $segment) {
            if (is_array($target) && array_key_exists($segment, $target)) {
                $target = $target[$segment];
            } else {
                return null;
            }
        }
        return $target;
    }

    /**
     * Legacy fallback mapper: builds an array structure for a given config file
     * using the legacy public properties of this class.
     *
     * @param string $file
     * @return array<string, mixed>|null
     */
    protected static function legacy(string $file): ?array
    {
        $legacy = new self();
        switch ($file) {
            case 'app':
                return [
                    'language_name' => $legacy->language_name,
                    'plugin_prefix' => $legacy->plugin_prefix,
                    'plugin_options' => $legacy->plugin_options,
                ];
            case 'hooks':
                return [
                    'post' => $legacy->post,
                    'get' => $legacy->get,
                    'add_filter' => $legacy->add_filter,
                    'add_action' => $legacy->add_action,
                ];
            case 'admin':
                return [
                    'dashboard' => $legacy->dashboard,
                    'files_dashboard' => $legacy->files_dashboard,
                    'menu' => $legacy->plugin_menu,
                ];
            case 'api':
                return [
                    'endpoint_name' => $legacy->api_endpoint_name,
                    'version' => $legacy->api_endpoint_version,
                    'routes' => $legacy->api_endpoints_functions,
                ];
            case 'shortcodes':
                return $legacy->shortcodes;
            case 'gutenberg':
                return [
                    'blocks' => $legacy->gutenberg_blocks,
                ];
            case 'post_types':
                return $legacy->post_types;
            case 'taxonomies':
                return $legacy->taxonomies;
            case 'widgets':
                return $legacy->widgets;
            default:
                return null;
        }
    }

    /*
     * Plugins option
     * storage in database the option value
     * Array ('option_name'=>'default value')
     * @example ["example_data" => 'foo',]
     * @return void
     */
    public $plugin_options = [];
    /**
     * Language Option
     * define a unique word for translate call
     */
    public $language_name = 'antonella';
    /**
     * Plugin text prefix
     * define a unique word for this plugin
     */
    public $plugin_prefix = 'ch_nella';
    /**
     * POST data process
     * get the post data and execute the function
     * @example ['post_data'=>'CH::function']
     */
    public $post = [
        'submit_antonella_config' => __NAMESPACE__ . '\Controllers\ExampleController::process_form',
    ];
    /**
     * GET data process
     * get the get data and execute the function
     * @example ['get_data'=>'CH::function']
     */
    public $get = [
    ];
    /**
     * add_filter data functions
     * @input array
     * @example ['body_class','CH::function',10,2]
     * @example ['body_class',[__NAMESPACE__.'\ExampleController,'function'],10,2]
     */
    public $add_filter = [
    ];
    /**
     * add_action data functions
     * @input array
     * @example ['body_class','CH::function',10,2]
     * @example ['body_class',[__NAMESPACE__.'\ExampleController','function'],10,2]
     */
    public $add_action = [
    ];
    /**
     * Custom shortcodes
     * Add custom shortcodes for your plugin
     * @example [['example', __NAMESPACE__.\ExampleController::example_shortcode']]
     */
    public $shortcodes = [
        // Add your custom shortcodes here
    ];

    /**
     * REST API Endpoints
     * Add custom REST API endpoints for your plugin
     * @example [['name', 'GET', __NAMESPACE__.\ApiController::index']]
     * @example Route: /wp-json/my-plugin-endpoint/v1/name
     */
    public $api_endpoint_name = 'my-plugin-endpoint';
    public $api_endpoint_version = 1;
    public $api_endpoints_functions = [
        // Add your custom API endpoints here
    ];

    /**
     * Gutenberg Blocks
     * Add custom Gutenberg blocks for your plugin
     */
    public $gutenberg_blocks = [
        // Add your custom Gutenberg blocks here
    ];
    /**
    * Dashboard

    * @reference: https://codex.wordpress.org/Function_Reference/wp_add_dashboard_widget
    */
    public $dashboard = [
        [
            'slug' => '',
            'name' => '',
            'function' => '', // example: __NAMESPACE__.'\Admin\PageAdmin::DashboardExample',
            'callback' => '',
            'args' => '',
        ]

    ];
    /**
     * Files for use in Dashboard
     */
    public $files_dashboard = [];

    /**
     * Plugin menu
     * Set your menu option here
     * @see Documentation: docs/configuration/plugin-menu.md for examples
     */
    public $plugin_menu = [
        [
            'path' => ['page'],
            'name' => 'Hello World',
            'function' => __NAMESPACE__ . "\Controllers\ExampleController::adminPage",
            'icon' => 'antonella-icon.png',
            'slug' => 'antonella-example',
        ]

        // Add your custom admin pages here
    ];

    /**
     * Custom Post Type
     * For creating custom post types in WordPress
     * @see Documentation: docs/configuration/custom-post-types.md for examples
     */
    public $post_types = [
        // Add your custom post types here
    ];

    /**
     * Taxonomies
     * For creating custom taxonomies for your post types
     * @see Documentation: docs/configuration/taxonomies.md for examples
     */
    public $taxonomies = [
        // Add your custom taxonomies here
    ];

    /**
     * Widget
     * For register a Widget please:
     * Console: php antonella Widget "NAME_OF_WIDGET"
     * @input array
     * @example public $widget = [__NAMESPACE__.'\YouClassWidget']  //only the class
     */
    public $widgets = [];
}
