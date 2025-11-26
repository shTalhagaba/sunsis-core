<?php

namespace Helpers;

class ApiHelper
{
    public static function dd($input)
    {
        echo '<div style="background: #FFBABA; padding: 20px 20px; border: 1px solid #ff0000;">';
        echo '<pre style="padding:0;margin:0;">';
        print_r($input);
        echo '</pre>';
        echo '</div>';
        die;
    }
}