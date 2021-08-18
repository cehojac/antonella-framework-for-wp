<?php
namespace  Antonella\CH;

if (!function_exists(__NAMESPACE__.'\dd')) {
    /**
     * Dump variables and die.
     */
    function dd()
    {
        call_user_func_array('dump', func_get_args());
        die();
    }
}

if (!function_exists(__NAMESPACE__.'\d')) {
    /**
     * Dump variable.
     */
    function d()
    {
        call_user_func_array('dump', func_get_args());
    }
}