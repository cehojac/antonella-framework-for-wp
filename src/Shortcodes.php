<?php

namespace CH;

use CH\Config;

class Shortcodes
{
    public function __construct()
    {
    }

    public static function index()
    {
        $filter = Config::get('shortcodes', []);
        // add_shortcode('example', array('Shortcodes','example_function'));
        if ($filter) {
            foreach ($filter as $data) {
                call_user_func_array('add_shortcode', [$data[0],$data[1]]);
            }
        }
    }
    /*
    * shortcode example
    * @info: https://codex.wordpress.org/Shortcode
    * @return string
    */
    public function example_function($atts)
    {
        extract(
            shortcode_atts(
                array(
                'data1' => 1,
                'data2'   => 1
                ),
                $atts
            )
        );
        return ("<div>$content</div>");
    }
}
