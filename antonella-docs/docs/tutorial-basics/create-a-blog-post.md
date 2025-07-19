---
sidebar_position: 2
---

# Configuration

To configure your plugin with Antonella Framework, you need to modify some key files. The configuration is done mainly through the `config.php` file and the main plugin file.

## Basic Plugin Configuration

### Plugin Name

To assign the plugin name, modify the `antonella-framework.php` file in the Plugin Name section:

```php
<?php
/**
 * Plugin Name: My Awesome Plugin
 * Description: Description of my plugin
 * Version: 1.0.0
 * Author: Your Name
 */
```

## config.php File

The `config.php` file contains an array system to configure your plugin in an easy and organized way.

### Options Variable

This variable allows you to introduce the options you want to store in WordPress:

```php
/**
 * Plugins option
 * storage in database the option value
 * Array ('option_name'=>'default value')
 * @example ["example_data" => 'foo',]
 * @return void
 */
public $plugin_options = [
    "version" => "1.0",
    "value1" => "some value",
    "my_option" => "default value",
];
```

### language_name Variable

You can specify a single word for translation. By default it's "antonella" but you can change it:

```php
/**
 * Language Option
 * define a unique word for translate call
 */
public $language_name = 'my_plugin';
```

### plugin_prefix Variable

Indicates a single word to identify the plugin. By default it's "ch_nella" but you can change it:

```php
/**
 * Plugin text prefix
 * define a unique word for this plugin
 */
public $plugin_prefix = 'my_plugin';
```

## POST and GET Data Handling

### data_post Variable

Controls incoming POST data. You can specify that if a variable within a POST interests you, a function should be invoked:

```php
/**
 * POST data process
 * get the post data and execute the function
 * @example ['post_data'=>'MyClass::myFunction']
 */
public $post = [
    "admin" => "MyClass::myFunction",
    "save_data" => "MyClass::saveData"
];
```

### data_get Variable

Similar to POST, handles GET variables:

```php
/**
 * GET data process
 * get the get data and execute the function
 * @example ['get_data'=>'MyClass::myFunction']
 */
public $get = [
    "my_get_variable" => "MyClass::myFunction"
];
```

## WordPress Hooks and Filters

### add_filter Variable

Here you can add [WordPress filters](https://developer.wordpress.org/reference/functions/add_filter/) with priority and parameters:

```php
/**
 * add_filter data functions
 * @input array
 * @example ['body_class','MyClass::myFunction',10,2]
 * @example ['body_class',['MyClass','myFunction'],10,2]
 */
public $add_filter = [
    ["init", "MyClass::myFunction", 10, 1],
    ["body_class", ["MyClass", "myFunction"], 10, 1],
];
```

### add_action Variable

Similar to filters, for [WordPress actions](https://developer.wordpress.org/reference/functions/add_action/):

```php
/**
 * add_action data functions
 * @input array
 * @example ['save_post','MyClass::myFunction',10,2]
 * @example ['save_post',['MyClass','myFunction'],10,2]
 */
public $add_action = [
    ["save_post", "MyClass::myFunction", 10, 1],
    ["wp_enqueue_scripts", ["MyClass", "myFunction"], 10, 1],
];
```

## Dashboard Configuration

### dashboard Variable

You can add sections to the WordPress admin dashboard:

```php
/**
 * Dashboard configuration
 * Add sections to WordPress admin dashboard
 */
public $dashboard = [
    [
        "title" => "My Plugin",
        "function" => "MyClass::dashboardWidget",
        "control" => "MyClass::dashboardControl"
    ]
];
```

## Next Step

Once you've configured the `config.php` file, your plugin will be ready to start developing functionality. In the next section, we'll learn the basic concepts for creating controllers and models.
