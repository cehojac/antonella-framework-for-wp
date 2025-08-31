<?php

return [
    // Core app config
    'language_name' => 'antonella',
    'plugin_prefix' => 'ch_nella',

    // Plugin options stored in DB (option_name => default value)
    // @example ['my_option_key' => 'default_value']
    'plugin_options' => [],

    // Optional: database table creation via dbDelta (used by Install)
    // @example
    // 'database_tables' => [
    //     [
    //         'name' => 'my_table',
    //         'columns' => "id BIGINT(20) unsigned NOT NULL AUTO_INCREMENT, name VARCHAR(191) NOT NULL, PRIMARY KEY (id)",
    //     ],
    // ],

    // Optional: add columns to existing tables (used by Install)
    // @example
    // 'table_updates' => [
    //     'my_table' => [
    //         ['name' => 'new_col', 'definition' => 'VARCHAR(191) NOT NULL DEFAULT ""'],
    //     ],
    // ],
];
