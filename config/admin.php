<?php

return [
    // Admin dashboard widgets and files
    'dashboard' => [
        // @example 
        //     'slug' => 'my_widget_slug',
        //     'name' => 'My Dashboard Widget',
        //     'function' => __NAMESPACE__ . '\\Admin\\PageAdmin::DashboardExample',
        //     'callback' => '',
        //     'args' => '',
        // 
    ],

    // @example ['/assets/css/dashboard-widget.css']
    'files_dashboard' => [],

    // Plugin menu pages
    'menu' => [
        [
                 'path' => ['page'],
                 'name' => 'Hello World',
                 'function' =>'CH\\Controllers\\ExampleController::adminPage',
                 'icon' => 'antonella-icon.png',
                 'slug' => 'antonella-example',
             ],
        // @example [
        //     'path' => ['page'],
        //     'name' => 'Hello World',
        //     'function' => __NAMESPACE__ . '\\Controllers\\ExampleController::adminPage',
        //     'icon' => 'antonella-icon.png',
        //     'slug' => 'antonella-example',
        // ],
    ],
];
