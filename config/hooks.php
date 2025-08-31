<?php

return [
    // @example 'post' => ['my_post_key' => __NAMESPACE__ . '\\Controllers\\MyController::handle_post']
    'post' => [
        'submit_antonella_config' => __NAMESPACE__ . '\\Controllers\\ExampleController::process_form',
    ],
    // @example 'get' => ['my_get_key' => __NAMESPACE__ . '\\Controllers\\MyController::handle_get']
    'get' => [
    ],
    // @example ['the_content', __NAMESPACE__ . '\\Controllers\\MyController::filter_content', 10, 1]
    'add_filter' => [
    ],
    // @example ['init', __NAMESPACE__ . '\\Controllers\\MyController::boot', 10, 0]
    'add_action' => [
    ],
];
