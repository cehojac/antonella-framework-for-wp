---
sidebar_position: 5
---

# Hooks and Filters

The Antonella Framework provides a powerful system for integrating with WordPress hooks and filters through the `Hooks.php` class and configuration in `Config.php`.

## Overview

The framework automatically handles:
- **Plugin lifecycle hooks** (activation, deactivation, uninstall)
- **WordPress filters** from your configuration
- **WordPress actions** (both predefined and custom)
- **Framework initialization** in the correct order

## Configuration in Config.php

### Add Filters

```php
public $add_filter = [
    ['body_class', __NAMESPACE__.'\MyController::add_body_class', 10, 1],
    ['the_content', __NAMESPACE__.'\MyController::modify_content', 20, 1],
    ['wp_title', [__NAMESPACE__.'\MyController', 'modify_title'], 10, 2],
];
```

### Add Actions

```php
public $add_action = [
    ['wp_enqueue_scripts', __NAMESPACE__.'\Assets::enqueue_scripts', 10],
    ['wp_footer', __NAMESPACE__.'\MyController::add_footer_content', 20],
    ['save_post', [__NAMESPACE__.'\MyController', 'on_save_post'], 10, 2],
];
```

## Filter Configuration

### Basic Structure

```php
[
    'hook_name',           // WordPress filter hook
    'callback_function',   // Your callback function
    priority,              // Priority (optional, default: 10)
    accepted_args          // Number of arguments (optional, default: 1)
]
```

### Examples

#### Simple Filter
```php
['body_class', __NAMESPACE__.'\Frontend::add_custom_body_class']
```

#### Filter with Priority
```php
['the_content', __NAMESPACE__.'\Content::modify_content', 20]
```

#### Filter with Arguments
```php
['wp_title', __NAMESPACE__.'\SEO::modify_title', 10, 2]
```

#### Class Method Filter
```php
['post_class', [__NAMESPACE__.'\Frontend', 'add_post_classes'], 10, 3]
```

## Action Configuration

### Basic Structure

```php
[
    'hook_name',           // WordPress action hook
    'callback_function',   // Your callback function
    priority,              // Priority (optional, default: 10)
    accepted_args          // Number of arguments (optional, default: 1)
]
```

### Examples

#### Simple Action
```php
['wp_enqueue_scripts', __NAMESPACE__.'\Assets::enqueue_styles']
```

#### Action with Priority
```php
['wp_footer', __NAMESPACE__.'\Analytics::add_tracking_code', 20]
```

#### Action with Arguments
```php
['save_post', __NAMESPACE__.'\PostHandler::on_save', 10, 2]
```

## Predefined Framework Actions

The framework automatically registers these actions:

### Admin Section
- `admin_menu` → `Admin\Admin::menu`
- `admin_init` → `Admin\PageAdmin::index`

### Initialization
- `init` → `Init::index` (priority 0)

### API
- `rest_api_init` → `Api::index`

### Content Types
- `init` → `Shortcodes::index`
- `init` → `PostTypes::index`
- `widgets_init` → `Widgets::index`

### Gutenberg
- `enqueue_block_editor_assets` → `Gutenberg::blocks`

### Dashboard
- `wp_dashboard_setup` → `Admin\Dashboard::index`

## Plugin Lifecycle Hooks

The framework automatically handles:

### Activation Hook
```php
register_activation_hook($plugin_file, [__NAMESPACE__.'\Install', 'index']);
```

### Deactivation Hook
```php
register_deactivation_hook($plugin_file, [__NAMESPACE__.'\Desactivate', 'index']);
```

### Uninstall Hook
```php
register_uninstall_hook($plugin_file, __NAMESPACE__.'\Uninstall::index');
```

## Creating Controller Methods

### Filter Example
```php
namespace CH;

class Frontend
{
    public static function add_custom_body_class($classes)
    {
        $classes[] = 'antonella-framework';
        return $classes;
    }
    
    public static function modify_content($content)
    {
        if (is_single()) {
            $content .= '<p>Enhanced by Antonella Framework</p>';
        }
        return $content;
    }
}
```

### Action Example
```php
namespace CH;

class Assets
{
    public static function enqueue_scripts()
    {
        wp_enqueue_script(
            'antonella-main',
            plugin_dir_url(__FILE__) . 'assets/js/main.js',
            ['jquery'],
            '1.0.0',
            true
        );
    }
    
    public static function enqueue_styles()
    {
        wp_enqueue_style(
            'antonella-style',
            plugin_dir_url(__FILE__) . 'assets/css/style.css',
            [],
            '1.0.0'
        );
    }
}
```

## Common WordPress Hooks

### Frontend Hooks
- `wp_enqueue_scripts` - Enqueue frontend scripts/styles
- `wp_head` - Add content to `<head>`
- `wp_footer` - Add content before `</body>`
- `the_content` - Modify post content
- `body_class` - Add CSS classes to body

### Admin Hooks
- `admin_enqueue_scripts` - Enqueue admin scripts/styles
- `admin_menu` - Add admin menu pages
- `admin_init` - Admin initialization
- `save_post` - When a post is saved
- `admin_notices` - Display admin notices

### Content Hooks
- `init` - WordPress initialization
- `wp_loaded` - After WordPress is fully loaded
- `template_redirect` - Before template is loaded
- `wp_ajax_*` - AJAX handlers
- `rest_api_init` - REST API initialization

## Best Practices

### 1. Use Namespaced Callbacks
```php
// Good
['init', __NAMESPACE__.'\MyController::init']

// Avoid
['init', 'my_function']
```

### 2. Set Appropriate Priorities
```php
// Early execution
['init', __NAMESPACE__.'\Setup::early_init', 5]

// Late execution
['wp_footer', __NAMESPACE__.'\Analytics::tracking', 20]
```

### 3. Validate Arguments
```php
public static function on_save_post($post_id, $post)
{
    if (!$post_id || !$post) {
        return;
    }
    
    if (wp_is_post_revision($post_id)) {
        return;
    }
    
    // Your logic here
}
```

### 4. Use Conditional Logic
```php
public static function frontend_only_action()
{
    if (is_admin()) {
        return;
    }
    
    // Frontend logic here
}
```

## Complete Example

```php
// In Config.php
public $add_filter = [
    ['body_class', __NAMESPACE__.'\Frontend::body_class'],
    ['the_content', __NAMESPACE__.'\Content::enhance_content', 10, 1],
];

public $add_action = [
    ['wp_enqueue_scripts', __NAMESPACE__.'\Assets::enqueue_frontend'],
    ['admin_enqueue_scripts', __NAMESPACE__.'\Assets::enqueue_admin'],
    ['save_post', __NAMESPACE__.'\PostHandler::on_save', 10, 2],
];
```

```php
// In your controller files
namespace CH;

class Frontend
{
    public static function body_class($classes)
    {
        $classes[] = 'antonella-powered';
        return $classes;
    }
}

class Content
{
    public static function enhance_content($content)
    {
        if (is_single() && in_the_loop()) {
            $content .= self::get_related_posts();
        }
        return $content;
    }
    
    private static function get_related_posts()
    {
        // Your related posts logic
        return '<div class="related-posts">...</div>';
    }
}
```

## Related Documentation

- [Configuration Overview](./config-overview.md)
- [WordPress Plugin API](https://developer.wordpress.org/plugins/hooks/)
- [WordPress Action Reference](https://codex.wordpress.org/Plugin_API/Action_Reference)
- [WordPress Filter Reference](https://codex.wordpress.org/Plugin_API/Filter_Reference)
