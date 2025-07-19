---
sidebar_position: 1
---

# Configuration Overview

The `Config.php` file is the heart of your Antonella Framework plugin configuration. It centralizes all the settings and features your plugin will use.

## File Location

```
src/Config.php
```

## Class Structure

```php
<?php
namespace CH;

class Config
{
    // Configuration properties...
}
```

## Main Configuration Sections

### 🔧 **Plugin Settings**
- `$plugin_options` - Database options storage
- `$language_name` - Translation domain
- `$plugin_prefix` - Unique plugin prefix

### 📄 **Content Types**
- `$post_types` - [Custom Post Types](./custom-post-types.md)
- `$taxonomies` - [Custom Taxonomies](./taxonomies.md)

### 🎛️ **Admin Interface**
- `$plugin_menu` - [Admin Menu Pages](./plugin-menu.md)
- `$dashboard` - Dashboard widgets

### 🔗 **WordPress Integration**
- `$add_action` - WordPress action hooks
- `$add_filter` - WordPress filter hooks
- `$shortcodes` - Custom shortcodes

### 🌐 **Data Processing**
- `$post` - POST data handlers
- `$get` - GET data handlers
- `$api_endpoints` - REST API endpoints

### 🎨 **Frontend Features**
- `$widget` - Custom widgets
- `$files_dashboard` - Dashboard assets

## Basic Configuration Example

```php
<?php
namespace CH;

class Config
{
    // Plugin identification
    public $language_name = 'my-plugin';
    public $plugin_prefix = 'mp_';
    
    // Plugin options stored in database
    public $plugin_options = [
        'enable_feature_x' => true,
        'api_key' => '',
        'max_items' => 10,
    ];
    
    // Simple custom post type
    public $post_types = [
        [
            'singular' => 'Product',
            'plural' => 'Products',
            'slug' => 'products',
            'position' => 12,
            'gutemberg' => true,
        ]
    ];
    
    // Simple admin page
    public $plugin_menu = [
        [
            "path" => ["page"],
            "name" => "My Plugin Settings",
            "function" => __NAMESPACE__."\Admin\Settings::index",
            "slug" => "my-plugin-settings",
        ]
    ];
}
```

## Configuration Best Practices

### 1. **Naming Conventions**
- Use descriptive names for all configurations
- Keep consistent naming patterns
- Use your plugin prefix to avoid conflicts

### 2. **Organization**
- Group related configurations together
- Comment complex configurations
- Use meaningful array keys

### 3. **Security**
- Validate all user inputs
- Sanitize data properly
- Use WordPress nonces for forms

### 4. **Performance**
- Only register what you need
- Use conditional loading when possible
- Cache expensive operations

## Configuration Sections Reference

| Section | Purpose | Documentation |
|---------|---------|---------------|
| Plugin Menu | Admin pages and subpages | [Plugin Menu](./plugin-menu.md) |
| Post Types | Custom content types | [Custom Post Types](./custom-post-types.md) |
| Taxonomies | Custom categorization | [Taxonomies](./taxonomies.md) |
| Hooks & Filters | WordPress integration | [Hooks & Filters](./hooks-filters.md) |

## Environment-Specific Configuration

You can use different configurations for different environments:

```php
public function __construct() 
{
    if (defined('WP_DEBUG') && WP_DEBUG) {
        // Development configuration
        $this->plugin_options['debug_mode'] = true;
    } else {
        // Production configuration
        $this->plugin_options['debug_mode'] = false;
    }
}
```

## Loading Configuration

The framework automatically loads your configuration. No additional setup required!

## Next Steps

1. [Configure Plugin Menus](./plugin-menu.md)
2. [Set up Custom Post Types](./custom-post-types.md)
3. [Create Custom Taxonomies](./taxonomies.md)
4. [Add Hooks and Filters](./hooks-filters.md)
