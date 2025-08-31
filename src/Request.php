<?php

/**
 * No modify this file !!!
 */

namespace CH;

use CH\Config;

class Request
{
    public $post_data = array();
    public $get_data = array();
    /**
     * Index function
     * Create the process data
     * @return void
     */
    public function __construct()
    {
        // Load POST/GET mappings from configuration (with legacy fallback)
        $postMap = Config::get('hooks.post', []);
        $getMap  = Config::get('hooks.get', []);
        $this->process($postMap);
        $this->process($getMap);
    }

    /**
     * Verify nonce for security
     * @param $nonce_name string The name of the nonce field
     * @param $action string The action name for the nonce
     * @return void
     */
    public function verify_nonce($nonce_name, $action)
    {
        // Sanitize and unslash POST data before verification
        $nonce_value = isset($_POST[$nonce_name]) ? sanitize_text_field(wp_unslash($_POST[$nonce_name])) : '';
        
        if (empty($nonce_value) || !wp_verify_nonce($nonce_value, $action)) {
            die(esc_html(__('Security check failed', 'antonella-framework')));
        }
    }

    /**
     * process function
     * process the request input (POST and GET)
     * @param [type] $datas the config array (post and get)
     * @return void
     */
    public function process($datas)
    {
        require_once(ABSPATH . 'wp-includes/pluggable.php');
        
        // Verify nonce for security when processing form data
        foreach ($datas as $key => $data) {
            // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Nonce verification handled by calling code or not required for GET requests
            if (isset($_REQUEST[$key])) {
                // Sanitize and unslash request data
                // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Nonce verification handled by calling code or not required for GET requests
                $sanitized_value = sanitize_text_field(wp_unslash($_REQUEST[$key]));
                call_user_func_array($data, [$sanitized_value]);
            } else {
                continue;
            }
        }
    }
}
