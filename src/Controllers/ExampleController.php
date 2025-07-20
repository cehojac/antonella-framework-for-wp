<?php

namespace CH\Controllers;

use CH\Security;

class ExampleController
{
    /**
     * Example form processing with security checks
     */
    public static function process_form()
    {
        // Check user capabilities
        Security::check_user_capability('manage_options');
        
        // Verify nonce
        Security::verify_nonce('example_nonce', 'example_action');
        
        // Sanitize input data
        $name = Security::sanitize_input($_POST['name'], 'text');
        $email = Security::sanitize_input($_POST['email'], 'email');
        $message = Security::sanitize_input($_POST['message'], 'textarea');
        
        // Process the data...
        // Your business logic here
        
        // Redirect or show success message
        wp_redirect(admin_url('admin.php?page=your-page&success=1'));
        exit;
    }
    
    /**
     * Example admin page with nonce
     */
    public static function admin_page()
    {
        // Check user capabilities
        Security::check_user_capability('manage_options');
        
        // Output the form
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(__('Example Form', 'antonella')); ?></h1>
            
            <form method="post" action="">
                <?php 
                // Create nonce field
                echo Security::create_nonce_field('example_action', 'example_nonce');
                ?>
                
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="name"><?php echo esc_html(__('Name', 'antonella')); ?></label>
                        </th>
                        <td>
                            <input type="text" id="name" name="name" class="regular-text" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="email"><?php echo esc_html(__('Email', 'antonella')); ?></label>
                        </th>
                        <td>
                            <input type="email" id="email" name="email" class="regular-text" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="message"><?php echo esc_html(__('Message', 'antonella')); ?></label>
                        </th>
                        <td>
                            <textarea id="message" name="message" rows="5" cols="50"></textarea>
                        </td>
                    </tr>
                </table>
                
                <?php submit_button(__('Save Changes', 'antonella')); ?>
            </form>
        </div>
        <?php
    }
    
    /**
     * Example API endpoint with security
     */
    public static function api_endpoint()
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
        $data = Security::sanitize_input($_REQUEST['data'], 'text');
        
        // Process and return response
        return rest_ensure_response([
            'success' => true,
            'data' => $data
        ]);
    }
    
    /**
     * Example AJAX handler
     */
    public static function ajax_handler()
    {
        // Check nonce
        if (!wp_verify_nonce($_POST['nonce'], 'example_ajax_nonce')) {
            wp_die(__('Security check failed', 'antonella'));
        }
        
        // Check capabilities
        Security::check_user_capability('edit_posts');
        
        // Process AJAX request
        $response = [
            'success' => true,
            'message' => __('AJAX request processed successfully', 'antonella')
        ];
        
        wp_send_json($response);
    }
}
