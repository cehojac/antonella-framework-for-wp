<?php

namespace CH;

class Security
{
    /**
     * Check user capabilities
     * @param string $capability The capability to check
     * @return bool|void Returns true if user has capability, dies if not
     */
    public static function check_user_capability($capability)
    {
        if (!current_user_can($capability)) {
            wp_die(esc_html(__('You do not have sufficient permissions to access this page.', 'antonella-framework')));
        }
        return true;
    }

    /**
     * Create nonce field
     * @param string $action The action name
     * @param string $name The nonce field name (optional)
     * @return string The nonce field HTML
     */
    public static function create_nonce_field($action, $name = '_wpnonce')
    {
        return wp_nonce_field($action, $name, true, false);
    }

    /**
     * Output nonce field directly (plugin checker friendly)
     * @param string $action The action name
     * @param string $name The nonce field name (optional)
     * @return void Outputs the nonce field directly
     */
    public static function nonce_field($action, $name = '_wpnonce')
    {
        wp_nonce_field($action, $name);
    }

    /**
     * Verify nonce
     * @param string $nonce_name The nonce field name
     * @param string $action The action name
     * @return bool|void Returns true if valid, dies if not
     */
    public static function verify_nonce($nonce_name, $action)
    {
        // Sanitize and unslash POST data before verification
        $nonce_value = isset($_POST[$nonce_name]) ? sanitize_text_field(wp_unslash($_POST[$nonce_name])) : '';
        
        if (empty($nonce_value) || !wp_verify_nonce($nonce_value, $action)) {
            wp_die(esc_html(__('Security check failed. Please try again.', 'antonella-framework')));
        }
        return true;
    }

    /**
     * Sanitize input data
     * @param mixed $data The data to sanitize
     * @param string $type The type of sanitization (text, email, url, etc.)
     * @return mixed Sanitized data
     */
    public static function sanitize_input($data, $type = 'text')
    {
        switch ($type) {
            case 'email':
                return sanitize_email($data);
            case 'url':
                return sanitize_url($data);
            case 'textarea':
                return sanitize_textarea_field($data);
            case 'html':
                return wp_kses_post($data);
            case 'text':
            default:
                return sanitize_text_field($data);
        }
    }

    /**
     * Escape output data
     * @param mixed $data The data to escape
     * @param string $type The type of escaping (html, attr, url, etc.)
     * @return mixed Escaped data
     */
    public static function escape_output($data, $type = 'html')
    {
        switch ($type) {
            case 'attr':
                return esc_attr($data);
            case 'url':
                return esc_url($data);
            case 'js':
                return esc_js($data);
            case 'html':
            default:
                return esc_html($data);
        }
    }

    /**
     * Check if current user is admin
     * @return bool
     */
    public static function is_admin_user()
    {
        return current_user_can('manage_options');
    }

    /**
     * Check if user can edit posts
     * @return bool
     */
    public static function can_edit_posts()
    {
        return current_user_can('edit_posts');
    }

    /**
     * Check if user can manage plugins
     * @return bool
     */
    public static function can_manage_plugins()
    {
        return current_user_can('activate_plugins');
    }
}
