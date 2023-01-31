<?php

namespace App\Helpers;

class Sanitize {

    public static function inputNumber($value)
    {
        $var = str_replace(['-', '.', '+'], '', $value);
        
        return filter_var($var, FILTER_SANITIZE_NUMBER_INT);
    }
}