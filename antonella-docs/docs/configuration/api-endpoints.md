---
sidebar_position: 6
---

# API Endpoints

The Antonella Framework provides a powerful REST API system that integrates seamlessly with WordPress REST API. You can easily create custom endpoints through configuration.

## Overview

The API system automatically:
- **Registers REST routes** based on your configuration
- **Handles versioning** with configurable API versions
- **Provides consistent structure** for all endpoints
- **Integrates with WordPress authentication** and permissions

## Configuration in Config.php

### Basic API Settings

```php
// API endpoint configuration
public $api_endpoint_name = 'antonella';
public $api_endpoint_version = '1';

// API endpoints functions
public $api_endpoints_functions = [
    ['users', 'GET', __NAMESPACE__.'\Api\Users::get'],
    ['posts', 'GET', __NAMESPACE__.'\Api\Posts::get'],
    ['posts', 'POST', __NAMESPACE__.'\Api\Posts::create'],
    ['posts', 'PUT', __NAMESPACE__.'\Api\Posts::update'],
    ['posts', 'DELETE', __NAMESPACE__.'\Api\Posts::delete'],
];
```

### API Endpoint Structure

Each endpoint is defined as an array with three elements:

```php
[
    'endpoint_name',    // The endpoint path
    'HTTP_METHOD',      // GET, POST, PUT, DELETE, etc.
    'callback_function' // The function to handle the request
]
```

## Generated API URLs

Based on your configuration, the framework generates URLs like:

```
https://yoursite.com/wp-json/antonella/v1/users/123
https://yoursite.com/wp-json/antonella/v1/posts/456
```

### URL Structure
```
/wp-json/{api_endpoint_name}/v{api_endpoint_version}/{endpoint_name}/{id}
```

## Creating API Controllers

### Basic Controller Structure

```php
<?php

namespace CH\Api;

class Users
{
    /**
     * Get user data
     * @param \WP_REST_Request $request
     * @return \WP_REST_Response|\WP_Error
     */
    public static function get($request)
    {
        $id = $request->get_param('id');
        
        // Validate ID
        if (!$id || !is_numeric($id)) {
            return new \WP_Error(
                'invalid_id',
                'Invalid user ID provided',
                ['status' => 400]
            );
        }
        
        // Get user data
        $user = get_user_by('ID', $id);
        
        if (!$user) {
            return new \WP_Error(
                'user_not_found',
                'User not found',
                ['status' => 404]
            );
        }
        
        // Return response
        return new \WP_REST_Response([
            'id' => $user->ID,
            'name' => $user->display_name,
            'email' => $user->user_email,
            'registered' => $user->user_registered,
        ], 200);
    }
}
```

### Advanced Controller with Permissions

```php
<?php

namespace CH\Api;

class Posts
{
    /**
     * Get post data
     */
    public static function get($request)
    {
        $id = $request->get_param('id');
        
        $post = get_post($id);
        
        if (!$post) {
            return new \WP_Error(
                'post_not_found',
                'Post not found',
                ['status' => 404]
            );
        }
        
        return new \WP_REST_Response([
            'id' => $post->ID,
            'title' => $post->post_title,
            'content' => $post->post_content,
            'status' => $post->post_status,
            'date' => $post->post_date,
        ], 200);
    }
    
    /**
     * Create new post
     */
    public static function create($request)
    {
        // Check permissions
        if (!current_user_can('publish_posts')) {
            return new \WP_Error(
                'insufficient_permissions',
                'You do not have permission to create posts',
                ['status' => 403]
            );
        }
        
        $title = $request->get_param('title');
        $content = $request->get_param('content');
        
        // Validate required fields
        if (empty($title)) {
            return new \WP_Error(
                'missing_title',
                'Post title is required',
                ['status' => 400]
            );
        }
        
        // Create post
        $post_id = wp_insert_post([
            'post_title' => sanitize_text_field($title),
            'post_content' => wp_kses_post($content),
            'post_status' => 'publish',
            'post_type' => 'post',
        ]);
        
        if (is_wp_error($post_id)) {
            return $post_id;
        }
        
        return new \WP_REST_Response([
            'id' => $post_id,
            'message' => 'Post created successfully',
        ], 201);
    }
    
    /**
     * Update existing post
     */
    public static function update($request)
    {
        $id = $request->get_param('id');
        
        // Check if post exists
        $post = get_post($id);
        if (!$post) {
            return new \WP_Error(
                'post_not_found',
                'Post not found',
                ['status' => 404]
            );
        }
        
        // Check permissions
        if (!current_user_can('edit_post', $id)) {
            return new \WP_Error(
                'insufficient_permissions',
                'You do not have permission to edit this post',
                ['status' => 403]
            );
        }
        
        $title = $request->get_param('title');
        $content = $request->get_param('content');
        
        $update_data = ['ID' => $id];
        
        if (!empty($title)) {
            $update_data['post_title'] = sanitize_text_field($title);
        }
        
        if (!empty($content)) {
            $update_data['post_content'] = wp_kses_post($content);
        }
        
        $result = wp_update_post($update_data);
        
        if (is_wp_error($result)) {
            return $result;
        }
        
        return new \WP_REST_Response([
            'id' => $id,
            'message' => 'Post updated successfully',
        ], 200);
    }
    
    /**
     * Delete post
     */
    public static function delete($request)
    {
        $id = $request->get_param('id');
        
        // Check if post exists
        $post = get_post($id);
        if (!$post) {
            return new \WP_Error(
                'post_not_found',
                'Post not found',
                ['status' => 404]
            );
        }
        
        // Check permissions
        if (!current_user_can('delete_post', $id)) {
            return new \WP_Error(
                'insufficient_permissions',
                'You do not have permission to delete this post',
                ['status' => 403]
            );
        }
        
        $result = wp_delete_post($id, true);
        
        if (!$result) {
            return new \WP_Error(
                'delete_failed',
                'Failed to delete post',
                ['status' => 500]
            );
        }
        
        return new \WP_REST_Response([
            'message' => 'Post deleted successfully',
        ], 200);
    }
}
```

## Advanced Configuration

### Custom Route Patterns

You can customize the route pattern by extending the Api class:

```php
// Custom endpoint without ID parameter
public $api_endpoints_functions = [
    ['search', 'GET', __NAMESPACE__.'\Api\Search::query'],
    ['stats', 'GET', __NAMESPACE__.'\Api\Stats::get'],
];
```

### Multiple HTTP Methods

```php
public $api_endpoints_functions = [
    ['users', 'GET', __NAMESPACE__.'\Api\Users::get'],
    ['users', 'POST', __NAMESPACE__.'\Api\Users::create'],
    ['users', 'PUT', __NAMESPACE__.'\Api\Users::update'],
    ['users', 'DELETE', __NAMESPACE__.'\Api\Users::delete'],
];
```

### Authentication and Permissions

```php
public static function secure_endpoint($request)
{
    // Check if user is logged in
    if (!is_user_logged_in()) {
        return new \WP_Error(
            'not_authenticated',
            'You must be logged in to access this endpoint',
            ['status' => 401]
        );
    }
    
    // Check specific capability
    if (!current_user_can('manage_options')) {
        return new \WP_Error(
            'insufficient_permissions',
            'You do not have sufficient permissions',
            ['status' => 403]
        );
    }
    
    // Your endpoint logic here
}
```

## Testing API Endpoints

### Using cURL

```bash
# GET request
curl -X GET "https://yoursite.com/wp-json/antonella/v1/users/1"

# POST request
curl -X POST "https://yoursite.com/wp-json/antonella/v1/posts/1" \
  -H "Content-Type: application/json" \
  -d '{"title":"New Post","content":"Post content"}'

# With authentication
curl -X GET "https://yoursite.com/wp-json/antonella/v1/users/1" \
  -H "Authorization: Bearer YOUR_JWT_TOKEN"
```

### Using JavaScript

```javascript
// GET request
fetch('/wp-json/antonella/v1/users/1')
  .then(response => response.json())
  .then(data => console.log(data));

// POST request
fetch('/wp-json/antonella/v1/posts/1', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json',
    'X-WP-Nonce': wpApiSettings.nonce
  },
  body: JSON.stringify({
    title: 'New Post',
    content: 'Post content'
  })
})
.then(response => response.json())
.then(data => console.log(data));
```

## Error Handling

### Standard Error Format

```php
return new \WP_Error(
    'error_code',           // Unique error code
    'Error message',        // Human-readable message
    ['status' => 400]       // HTTP status code
);
```

### Common HTTP Status Codes

- **200** - Success
- **201** - Created
- **400** - Bad Request
- **401** - Unauthorized
- **403** - Forbidden
- **404** - Not Found
- **500** - Internal Server Error

## Best Practices

### 1. Validate Input Data

```php
public static function create($request)
{
    $title = sanitize_text_field($request->get_param('title'));
    $content = wp_kses_post($request->get_param('content'));
    
    if (empty($title)) {
        return new \WP_Error('missing_title', 'Title is required', ['status' => 400]);
    }
    
    // Continue with logic...
}
```

### 2. Use Proper HTTP Methods

- **GET** - Retrieve data
- **POST** - Create new resources
- **PUT** - Update existing resources
- **DELETE** - Remove resources

### 3. Implement Proper Authentication

```php
// Check user permissions
if (!current_user_can('required_capability')) {
    return new \WP_Error('insufficient_permissions', 'Access denied', ['status' => 403]);
}
```

### 4. Return Consistent Response Format

```php
// Success response
return new \WP_REST_Response([
    'success' => true,
    'data' => $data,
    'message' => 'Operation completed successfully'
], 200);

// Error response
return new \WP_Error(
    'operation_failed',
    'Operation could not be completed',
    ['status' => 400]
);
```

## Complete Example

```php
// In Config.php
public $api_endpoint_name = 'antonella';
public $api_endpoint_version = '1';

public $api_endpoints_functions = [
    ['products', 'GET', __NAMESPACE__.'\Api\Products::get'],
    ['products', 'POST', __NAMESPACE__.'\Api\Products::create'],
    ['categories', 'GET', __NAMESPACE__.'\Api\Categories::get'],
];
```

This configuration creates these endpoints:
- `GET /wp-json/antonella/v1/products/123`
- `POST /wp-json/antonella/v1/products/123`
- `GET /wp-json/antonella/v1/categories/456`

## Related Documentation

- [Configuration Overview](./config-overview.md)
- [Hooks and Filters](./hooks-filters.md)
- [WordPress REST API](https://developer.wordpress.org/rest-api/)
- [WordPress REST API Handbook](https://developer.wordpress.org/rest-api/extending-the-rest-api/)
