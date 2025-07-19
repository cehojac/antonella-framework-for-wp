<?php

namespace CH;

use CH\Config;

/**
 * WordPress REST API integration for Antonella Framework
 * @see Documentation: docs/configuration/api-endpoints.md for examples
 */
class Api
{
    /**
     * Register all API endpoints from configuration
     * Called automatically by the Hooks system
     */
    public static function index()
    {
        $config = new Config();
        $endpoints = $config->api_endpoints_functions;
        
        if (!empty($endpoints)) {
            self::register_endpoints($config, $endpoints);
        }
    }
    
    /**
     * Register REST API endpoints
     * @param Config $config Framework configuration
     * @param array $endpoints Array of endpoint configurations
     */
    private static function register_endpoints($config, $endpoints)
    {
        $namespace = $config->api_endpoint_name . '/v' . $config->api_endpoint_version;
        
        foreach ($endpoints as $endpoint) {
            if (self::is_valid_endpoint($endpoint)) {
                \register_rest_route(
                    $namespace,
                    '/' . $endpoint[0] . '/(?P<id>\d+)',
                    [
                        'methods' => $endpoint[1],
                        'callback' => $endpoint[2],
                        'permission_callback' => '__return_true', // Override in your callbacks
                    ]
                );
            }
        }
    }
    
    /**
     * Validate endpoint configuration
     * @param array $endpoint Endpoint configuration array
     * @return bool True if valid, false otherwise
     */
    private static function is_valid_endpoint($endpoint)
    {
        return isset($endpoint[0], $endpoint[1], $endpoint[2]) &&
               !empty($endpoint[0]) &&
               !empty($endpoint[1]) &&
               is_callable($endpoint[2]);
    }
}
