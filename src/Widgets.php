<?php

namespace Antonella\CH;

class Widgets
{
    public function __construct()
    {
    }

    public static function index()
    {
        $config = new Config();
        $filter = $config->widgets;
        if (is_array($filter) && count($filter) > 0) {
            foreach ($filter as $data) {
                \register_widget($data);
            }
        }
    }
}
