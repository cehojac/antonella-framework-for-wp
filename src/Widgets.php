<?php

namespace CH;

use CH\Config;

class Widgets
{
    public function __construct()
    {
    }

    public static function index()
    {
        $filter = Config::get('widgets', []);
        // add_shortcode('example', array('Shortcodes','example_function'));
        if (is_array($filter) && count($filter) > 0) {
            foreach ($filter as $data) {
                \register_widget($data);
            }
        }
    }
}
