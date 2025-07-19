---
sidebar_position: 4
---

# Taxonomies Configuration

The `$taxonomies` variable in `Config.php` allows you to create custom taxonomies for your custom post types, providing powerful categorization and tagging capabilities.

## Basic Structure

```php
public $taxonomies = [
    [
        'post_type'     => 'products',
        'singular'      => 'Category',
        'plural'        => 'Categories',
        'slug'          => 'product-categories',
        'gutemberg'     => true,
    ]
];
```

## Configuration Options

### Basic Options

| Parameter | Type | Description | Required |
|-----------|------|-------------|----------|
| `post_type` | string | The post type this taxonomy applies to | ✅ |
| `singular` | string | Singular name (e.g., "Category") | ✅ |
| `plural` | string | Plural name (e.g., "Categories") | ✅ |
| `slug` | string | URL slug for the taxonomy | ✅ |
| `gutemberg` | bool | Enable/disable in Gutenberg editor | ❌ |

### Advanced Options

| Parameter | Type | Description |
|-----------|------|-------------|
| `labels` | array | Custom labels for the taxonomy |
| `args` | array | Additional arguments for `register_taxonomy()` |
| `rewrite` | array | URL rewrite rules |
| `capabilities` | array | Custom capabilities |

## Examples

### Simple Category Taxonomy

```php
[
    'post_type'     => 'products',
    'singular'      => 'Category',
    'plural'        => 'Categories',
    'slug'          => 'product-categories',
    'gutemberg'     => true,
]
```

### Tag-like Taxonomy

```php
[
    'post_type'     => 'products',
    'singular'      => 'Tag',
    'plural'        => 'Tags',
    'slug'          => 'product-tags',
    'gutemberg'     => true,
]
```

### Advanced Taxonomy Configuration

```php
[
    'post_type'     => 'events',
    'singular'      => 'Event Type',
    'plural'        => 'Event Types',
    'slug'          => 'event-types',
    'gutemberg'     => true,
    
    // Advanced configuration
    'labels'        => [
        'search_items'      => 'Search Event Types',
        'all_items'         => 'All Event Types',
        'parent_item'       => 'Parent Event Type',
        'parent_item_colon' => 'Parent Event Type:',
        'edit_item'         => 'Edit Event Type',
        'update_item'       => 'Update Event Type',
        'add_new_item'      => 'Add New Event Type',
        'new_item_name'     => 'New Event Type Name',
        'menu_name'         => 'Event Types',
    ],
    'args'          => [
        'hierarchical'      => true,  // Like categories
        'public'            => true,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'show_in_rest'      => true, // For Gutenberg
    ],
    'rewrite'       => [
        'slug'              => 'event-type',
        'with_front'        => false,
        'hierarchical'      => true,
    ],
]
```

## Hierarchical vs Non-Hierarchical

### Hierarchical (Like Categories)
```php
'args' => [
    'hierarchical' => true,  // Allows parent/child relationships
]
```

### Non-Hierarchical (Like Tags)
```php
'args' => [
    'hierarchical' => false, // Flat structure, no parent/child
]
```

## Multiple Taxonomies for One Post Type

```php
public $taxonomies = [
    // Categories for products
    [
        'post_type'     => 'products',
        'singular'      => 'Category',
        'plural'        => 'Categories',
        'slug'          => 'product-categories',
        'gutemberg'     => true,
        'args'          => [
            'hierarchical' => true,
        ]
    ],
    // Tags for products
    [
        'post_type'     => 'products',
        'singular'      => 'Tag',
        'plural'        => 'Tags',
        'slug'          => 'product-tags',
        'gutemberg'     => true,
        'args'          => [
            'hierarchical' => false,
        ]
    ],
    // Brands for products
    [
        'post_type'     => 'products',
        'singular'      => 'Brand',
        'plural'        => 'Brands',
        'slug'          => 'product-brands',
        'gutemberg'     => true,
    ],
];
```

## Complete Example with Multiple Post Types

```php
public $taxonomies = [
    // Product taxonomies
    [
        'post_type'     => 'products',
        'singular'      => 'Product Category',
        'plural'        => 'Product Categories',
        'slug'          => 'product-categories',
        'gutemberg'     => true,
    ],
    [
        'post_type'     => 'products',
        'singular'      => 'Product Tag',
        'plural'        => 'Product Tags',
        'slug'          => 'product-tags',
        'gutemberg'     => true,
    ],
    
    // Service taxonomies
    [
        'post_type'     => 'services',
        'singular'      => 'Service Type',
        'plural'        => 'Service Types',
        'slug'          => 'service-types',
        'gutemberg'     => true,
    ],
    
    // Portfolio taxonomies
    [
        'post_type'     => 'portfolio',
        'singular'      => 'Portfolio Category',
        'plural'        => 'Portfolio Categories',
        'slug'          => 'portfolio-categories',
        'gutemberg'     => true,
    ],
];
```

## Taxonomy Arguments Reference

### Common Arguments

```php
'args' => [
    'hierarchical'      => true,        // true = categories, false = tags
    'public'            => true,        // Show in admin and frontend
    'show_ui'           => true,        // Show admin interface
    'show_admin_column' => true,        // Show column in post list
    'show_in_nav_menus' => true,        // Show in navigation menus
    'show_tagcloud'     => true,        // Show in tag cloud widget
    'query_var'         => true,        // Enable query var
    'show_in_rest'      => true,        // Enable for Gutenberg/REST API
]
```

### Capabilities

```php
'capabilities' => [
    'manage_terms' => 'manage_categories',
    'edit_terms'   => 'manage_categories',
    'delete_terms' => 'manage_categories',
    'assign_terms' => 'edit_posts',
]
```

## Best Practices

1. **Use descriptive names** - Make taxonomy purpose clear
2. **Plan hierarchy** - Decide if you need parent/child relationships
3. **Consider SEO** - Use SEO-friendly slugs
4. **Enable Gutenberg** - For modern editor compatibility
5. **Test relationships** - Verify post type associations work
6. **Consistent naming** - Use consistent naming conventions

## Usage in Templates

### Get taxonomy terms for a post
```php
$terms = get_the_terms($post_id, 'product-categories');
if ($terms && !is_wp_error($terms)) {
    foreach ($terms as $term) {
        echo $term->name;
    }
}
```

### Query posts by taxonomy
```php
$args = [
    'post_type' => 'products',
    'tax_query' => [
        [
            'taxonomy' => 'product-categories',
            'field'    => 'slug',
            'terms'    => 'electronics',
        ],
    ],
];
$query = new WP_Query($args);
```

## Related Documentation

- [Custom Post Types Configuration](./custom-post-types.md)
- [WordPress register_taxonomy()](https://developer.wordpress.org/reference/functions/register_taxonomy/)
