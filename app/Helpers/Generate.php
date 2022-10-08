<?php

namespace App\Helpers;

class Generate 
{
    public static function randomUsername($string) {
        $username = vsprintf('%s%s%d', [...sscanf(strtolower($string), '%s %2s'), random_int(0, 100)]);

        return $username;
    }
}
