---
sidebar_position: 2
---

# Plugin Menu Configuration

The `$plugin_menu` variable in `Config.php` allows you to create custom admin pages and subpages in the WordPress admin panel.

## Basic Structure

```php
public $plugin_menu = [
    [
        "path"      => ["page"],
        "name"      => "My Custom Page",
        "function"  => __NAMESPACE__."\Admin\PageAdmin::index",
        "icon"      => "antonella-icon.png",
        "slug"      => "my-custom-page",
    ]
];
```

## Configuration Options

| Parameter | Type | Description |
|-----------|------|-------------|
| `path` | array | Defines where the menu appears (["page"], ["option"], etc.) |
| `name` | string | Display name in the admin menu |
| `function` | string | Callback function to render the page |
| `icon` | string | Icon file name (optional) |
| `slug` | string | URL slug for the page |
| `subpages` | array | Array of subpages (optional) |

## Examples

### Simple Page

```php
[
    "path"      => ["page"],
    "name"      => "My Custom Page",
    "function"  => __NAMESPACE__."\Admin\PageAdmin::index",
    "icon"      => "antonella-icon.png",
    "slug"      => "my-custom-page",
]
```

### Page with Subpages

```php
[
    "path"      => ["page"],
    "name"      => "My Custom Page",
    "function"  => __NAMESPACE__."\Admin::option_page",
    "slug"      => "my-custom-page",
    "subpages"  => [
        [
            "name"      => "My Custom sub Page",
            "slug"      => "my-top-sub-level-slug",
            "function"  => __NAMESPACE__."\Admin::option_page",
        ],
        [
            "name"      => "My Second Custom sub Page",
            "slug"      => "my-second-sub-level-slug",
            "function"  => __NAMESPACE__."\Admin::option_page",
        ],
    ]
]
```

### Multiple Top-Level Pages

```php
public $plugin_menu = [
    [
        "path"      => ["page"],
        "name"      => "My Custom Page",
        "function"  => __NAMESPACE__."\Admin::option_page",
        "icon"      => "antonella-icon.png",
        "slug"      => "my-custom-page",
        "subpages"  => [
            [
                "name"      => "My Custom sub Page",
                "slug"      => "my-top-sub-level-slug",
                "function"  => __NAMESPACE__."\Admin::option_page",
            ],
            [
                "name"      => "My Second Custom sub Page",
                "slug"      => "my-second-sub-level-slug",
                "function"  => __NAMESPACE__."\Admin::option_page",
            ],
        ]
    ],
    [
        "path"      => ["page"],
        "name"      => "My SECOND Custom Page",
        "function"  => __NAMESPACE__."\Admin::option_page",
        "icon"      => "antonella-icon.png",
        "slug"      => "my-SECOND-custom-page",
        "subpages"  => [
            [
                "name"      => "My Custom sub Page",
                "slug"      => "my-top-sub-level-slug-2",
                "function"  => __NAMESPACE__."\Admin::option_page",
            ],
        ]
    ],
];
```

### Options Page

```php
[
    "path"      => ["option"],
    "name"      => "sub page in option",
    "slug"      => "sub-option",
    "function"  => __NAMESPACE__."\Admin::option_page",
]
```

## Path Types

- `["page"]` - Creates a top-level admin page
- `["option"]` - Creates a page under Settings
- `["theme"]` - Creates a page under Appearance
- `["plugin"]` - Creates a page under Plugins

## Controller Functions

Make sure your controller functions exist and are properly namespaced:

```php
namespace CH\Admin;

class PageAdmin 
{
    public static function index() 
    {
        // Your page content here
        echo '<div class="wrap">';
        echo '<h1>My Custom Page</h1>';
        echo '<p>Welcome to my custom admin page!</p>';
        echo '</div>';
    }
}
```

## Best Practices

1. **Use descriptive slugs** - Make them unique and meaningful
2. **Organize with subpages** - Group related functionality
3. **Consistent naming** - Use a consistent naming convention
4. **Proper icons** - Use appropriate icons for better UX
5. **Security** - Always validate and sanitize user input in your functions
