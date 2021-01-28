<?php

if (!function_exists('dd')) {
    /**
     * Dump variables and die.
     */
    function dd()
    {
        call_user_func_array('dump', func_get_args());
        die();
    }
}

if (!function_exists('d')) {
    /**
     * Dump variable.
     */
    function d()
    {
        call_user_func_array('dump', func_get_args());
    }
}