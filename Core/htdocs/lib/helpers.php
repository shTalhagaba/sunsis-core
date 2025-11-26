<?php

if (!function_exists('dump')) {
    function dump(...$args)
    {
        foreach ($args as $var) {
            echo '<div style="background: #FFBABA; padding: 20px 20px; border: 1px solid #ff0000;">';
            echo '<pre style="padding:0;margin:0;">';
            print_r($var);
            echo '</pre>';
            echo '</div>';
        }
    }
}

if (!function_exists('dd')) {
    function dd(...$args)
    {
        dump(...$args);
        die();
    }
}