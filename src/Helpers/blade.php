<?php

if (class_exists('Jenssegers\Blade\Blade') && !function_exists('view')) {
    function view($BladePage, $Attributes = [])
    {
        $path = dirname(__DIR__, 2);
        $blade = new Jenssegers\Blade\Blade($path . '/resources/views', $path . '/storage/cache');
        return $blade->render($BladePage, $Attributes);
    }
}
