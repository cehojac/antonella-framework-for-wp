# 🔒 Antonella Framework Security Guide

## Overview

Antonella Framework 1.9.0 now includes comprehensive security features to ensure your WordPress plugins follow best practices for security.

## Security Features

### 1. 🛡️ User Capability Checks

#### Basic Usage:
```php
use CH\Security;

// Check if user has specific capability
Security::check_user_capability('manage_options');

// Check if user is admin
if (Security::is_admin_user()) {
    // Admin-only code
}

// Check if user can edit posts
if (Security::can_edit_posts()) {
    // Editor-level code
}
```

#### Common WordPress Capabilities:
- `manage_options` - Administrator level
- `edit_posts` - Editor level
- `edit_published_posts` - Author level
- `edit_others_posts` - Editor level
- `publish_posts` - Author level
- `activate_plugins` - Administrator level

### 2. 🔐 Nonce Security

#### Creating Nonce Fields:
```php
// In your admin form
echo Security::create_nonce_field('my_action', 'my_nonce');

// This generates:
// <input type="hidden" name="my_nonce" value="abc123..." />
```

#### Verifying Nonces:
```php
// When processing form data
Security::verify_nonce('my_nonce', 'my_action');
```

### 3. 🧹 Input Sanitization

#### Sanitize Different Data Types:
```php
// Text fields
$name = Security::sanitize_input($_POST['name'], 'text');

// Email addresses
$email = Security::sanitize_input($_POST['email'], 'email');

// URLs
$website = Security::sanitize_input($_POST['website'], 'url');

// Textarea content
$message = Security::sanitize_input($_POST['message'], 'textarea');

// HTML content (allows safe HTML)
$content = Security::sanitize_input($_POST['content'], 'html');
```

### 4. 🔒 Output Escaping

#### Escape Data for Different Contexts:
```php
// HTML content
echo Security::escape_output($data, 'html');

// HTML attributes
echo '<div class="' . Security::escape_output($class, 'attr') . '">';

// URLs
echo '<a href="' . Security::escape_output($url, 'url') . '">';

// JavaScript
echo '<script>var data = "' . Security::escape_output($data, 'js') . '";</script>';
```

## Complete Example

### Admin Page with Security:
```php
namespace CH\Controllers;

use CH\Security;

class MyController
{
    public static function admin_page()
    {
        // Check user capabilities
        Security::check_user_capability('manage_options');
        
        // Process form if submitted
        if (isset($_POST['submit'])) {
            // Verify nonce
            Security::verify_nonce('my_nonce', 'my_action');
            
            // Sanitize input
            $option_value = Security::sanitize_input($_POST['option_value'], 'text');
            
            // Save option
            update_option('my_option', $option_value);
            
            // Show success message
            echo '<div class="notice notice-success"><p>' . 
                 Security::escape_output(__('Settings saved!', 'antonella')) . 
                 '</p></div>';
        }
        
        // Get current option value
        $current_value = get_option('my_option', '');
        ?>
        <div class="wrap">
            <h1><?php echo Security::escape_output(__('My Settings', 'antonella')); ?></h1>
            
            <form method="post" action="">
                <?php echo Security::create_nonce_field('my_action', 'my_nonce'); ?>
                
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="option_value">
                                <?php echo Security::escape_output(__('Option Value', 'antonella')); ?>
                            </label>
                        </th>
                        <td>
                            <input type="text" 
                                   id="option_value" 
                                   name="option_value" 
                                   value="<?php echo Security::escape_output($current_value, 'attr'); ?>" 
                                   class="regular-text" />
                        </td>
                    </tr>
                </table>
                
                <?php submit_button(__('Save Settings', 'antonella')); ?>
            </form>
        </div>
        <?php
    }
}
```

### AJAX Handler with Security:
```php
public static function ajax_handler()
{
    // Check nonce
    if (!wp_verify_nonce($_POST['nonce'], 'my_ajax_action')) {
        wp_send_json_error(__('Security check failed', 'antonella'));
    }
    
    // Check capabilities
    Security::check_user_capability('edit_posts');
    
    // Sanitize input
    $data = Security::sanitize_input($_POST['data'], 'text');
    
    // Process request
    $result = my_process_function($data);
    
    // Send response
    wp_send_json_success([
        'message' => __('Success!', 'antonella'),
        'data' => $result
    ]);
}
```

### API Endpoint with Security:
```php
public static function api_endpoint(\WP_REST_Request $request)
{
    // Check if user is logged in
    if (!is_user_logged_in()) {
        return new \WP_Error('unauthorized', __('You must be logged in', 'antonella'), ['status' => 401]);
    }
    
    // Check capabilities
    if (!Security::can_edit_posts()) {
        return new \WP_Error('forbidden', __('Insufficient permissions', 'antonella'), ['status' => 403]);
    }
    
    // Sanitize input
    $data = Security::sanitize_input($request->get_param('data'), 'text');
    
    // Process and return response
    return rest_ensure_response([
        'success' => true,
        'data' => $data
    ]);
}
```

## Best Practices

1. **Always check capabilities** before allowing access to admin functions
2. **Use nonces** for all forms and AJAX requests
3. **Sanitize all input** data before processing
4. **Escape all output** data before displaying
5. **Use specific capabilities** rather than generic ones when possible
6. **Validate data types** and ranges where appropriate
7. **Log security events** for monitoring purposes

## Common Security Mistakes to Avoid

❌ **Don't do this:**
```php
// No capability check
function admin_function() {
    update_option('my_option', $_POST['value']); // No sanitization
}

// No nonce verification
if (isset($_POST['submit'])) {
    process_form(); // Vulnerable to CSRF
}

// No output escaping
echo '<div>' . $user_input . '</div>'; // XSS vulnerability
```

✅ **Do this instead:**
```php
// Proper security implementation
function admin_function() {
    Security::check_user_capability('manage_options');
    Security::verify_nonce('my_nonce', 'my_action');
    
    $value = Security::sanitize_input($_POST['value'], 'text');
    update_option('my_option', $value);
}

// In template
echo '<div>' . Security::escape_output($user_input) . '</div>';
```


