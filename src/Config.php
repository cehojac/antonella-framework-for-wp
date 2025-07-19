<?php

namespace CH;

class Config
{
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
        'slug'      => '',
        'name'      => '',
        'function'  => '', // example: __NAMESPACE__.'\Admin\PageAdmin::DashboardExample',
        'callback'  => '',
        'args'      => '',
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
