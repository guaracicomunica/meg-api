<?php

namespace App\Utils;

class StringUtil
{
    public static function str_replace_limit($search, $replace, $string, $limit = 1) {
        $pos = strpos($string, $search);

        if ($pos === false) {
            return $string;
        }

        $searchLen = strlen($search);

        for ($i = 0; $i < $limit; $i++) {
            $string = substr_replace($string, $replace, $pos, $searchLen);

            $pos = strpos($string, $search);

            if ($pos === false) {
                break;
            }
        }

        return $string;
    }

}
