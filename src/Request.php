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
        $config = new Config();
        $this->process($config->post);
        $this->process($config->get);
    }
    
    /**
     * Verify nonce for security
     * @param $nonce_name string The name of the nonce field
     * @param $action string The action name for the nonce
     * @return void
     */
    public function verify_nonce($nonce_name, $action)
    {
        if (!isset($_POST[$nonce_name]) || !wp_verify_nonce($_POST[$nonce_name], $action)) {
            die(__('Security check failed', 'antonella'));
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
        foreach ($datas as $key => $data) {
            if (isset($_REQUEST[$key])) {
                call_user_func_array($data, [sanitize_text_field($_REQUEST[$key])]);
            } else {
                continue;
            }
        }
    }
}
