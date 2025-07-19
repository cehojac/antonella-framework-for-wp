---
sidebar_position: 3
---

# Custom Post Types

The `$post_types` variable in `Config.php` allows you to easily create custom post types in WordPress with advanced features.

## Basic Structure

```php
public $post_types = [
    [
        'singular'      => 'Product',
        'plural'        => 'Products',
        'slug'          => 'products',
        'position'      => 12,
        'taxonomy'      => ['categories','tags'],
        'image'         => 'antonella-icon.png',
        'gutemberg'     => true,
    ],
];
```

## Configuration Options

### Basic Options

| Parameter | Type | Description | Required |
|-----------|------|-------------|----------|
| `singular` | string | Singular name (e.g., "Product") | ✅ |
| `plural` | string | Plural name (e.g., "Products") | ✅ |
| `slug` | string | URL slug for the post type | ✅ |
| `position` | int | Menu position in admin (5=below Posts, 10=below Media, etc.) | ❌ |
| `taxonomy` | array | Associated taxonomies | ❌ |
| `image` | string | Menu icon filename | ❌ |
| `gutemberg` | bool | Enable/disable Gutenberg editor | ❌ |

### Advanced Options

| Parameter | Type | Description |
|-----------|------|-------------|
| `labels` | array | Custom labels for the post type |
| `args` | array | Additional arguments for `register_post_type()` |
| `rewrite` | array | URL rewrite rules |

## Examples

### Simple Post Type

```php
[
    'singular'      => 'Product',
    'plural'        => 'Products',
    'slug'          => 'products',
    'position'      => 12,
    'gutemberg'     => true,
],
```

### Post Type with Taxonomies

```php
[
    'singular'      => 'Portfolio Item',
    'plural'        => 'Portfolio',
    'slug'          => 'portfolio',
    'position'      => 15,
    'taxonomy'      => ['portfolio-categories', 'portfolio-tags'],
    'image'         => 'portfolio-icon.png',
    'gutemberg'     => true,
],
```

### Advanced Post Type Configuration

```php
[
    'singular'      => 'Event',
    'plural'        => 'Events',
    'slug'          => 'events',
    'position'      => 8,
    'taxonomy'      => ['event-categories'],
    'image'         => 'events-icon.png',
    'gutemberg'     => false,
    
    // Advanced configuration
    'labels'        => [
        'add_new_item'      => 'Add New Event',
        'edit_item'         => 'Edit Event',
        'new_item'          => 'New Event',
        'view_item'         => 'View Event',
        'search_items'      => 'Search Events',
        'not_found'         => 'No events found',
        'not_found_in_trash'=> 'No events found in trash',
    ],
    'args'          => [
        'public'            => true,
        'publicly_queryable'=> true,
        'show_ui'           => true,
        'show_in_menu'      => true,
        'query_var'         => true,
        'capability_type'   => 'post',
        'has_archive'       => true,
        'hierarchical'      => false,
        'supports'          => ['title', 'editor', 'thumbnail', 'excerpt'],
        'show_in_rest'      => true, // For Gutenberg
    ],
    'rewrite'       => [
        'slug'              => 'events',
        'with_front'        => false,
    ]
],
```

## Menu Positions

Common WordPress menu positions:

- `5` - Below Posts
- `10` - Below Media
- `15` - Below Links
- `20` - Below Pages
- `25` - Below Comments
- `60` - Below first separator
- `65` - Below Plugins
- `70` - Below Users
- `75` - Below Tools
- `80` - Below Settings

## Supported Features

You can control which features your post type supports:

```php
'supports' => [
    'title',        // Post title
    'editor',       // Content editor
    'thumbnail',    // Featured image
    'excerpt',      // Post excerpt
    'comments',     // Comments
    'trackbacks',   // Trackbacks
    'custom-fields',// Custom fields
    'revisions',    // Post revisions
    'page-attributes', // Menu order, parent
    'post-formats', // Post formats
]
```

## Multiple Post Types Example

```php
public $post_types = [
    [
        'singular'      => 'Product',
        'plural'        => 'Products',
        'slug'          => 'products',
        'position'      => 12,
        'taxonomy'      => ['product-categories', 'product-tags'],
        'image'         => 'product-icon.png',
        'gutemberg'     => true,
    ],
    [
        'singular'      => 'Service',
        'plural'        => 'Services',
        'slug'          => 'services',
        'position'      => 13,
        'taxonomy'      => ['service-categories'],
        'image'         => 'service-icon.png',
        'gutemberg'     => true,
    ],
    [
        'singular'      => 'Testimonial',
        'plural'        => 'Testimonials',
        'slug'          => 'testimonials',
        'position'      => 14,
        'gutemberg'     => false,
    ],
];
```

## Best Practices

1. **Use descriptive names** - Make singular/plural names clear
2. **Choose appropriate slugs** - Use SEO-friendly URLs
3. **Set logical positions** - Group related post types together
4. **Consider Gutenberg** - Enable only when needed
5. **Plan taxonomies** - Think about categorization needs
6. **Test thoroughly** - Verify all features work as expected

## Related Documentation

- [Taxonomies Configuration](./taxonomies.md)
- [WordPress register_post_type()](https://developer.wordpress.org/reference/functions/register_post_type/)
