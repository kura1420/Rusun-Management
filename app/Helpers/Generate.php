<?php

namespace App\Helpers;

class Generate 
{
    public static function randomUsername($string) {
        $explode = explode(' ', $string);
        $md5 = md5($string);
        $substr = substr($md5, 0, 3);

        $username = $explode[0] . '.' . $substr . rand(1111, 9999);

        return strtolower($username);
    }
}
