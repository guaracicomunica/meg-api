<?php

namespace App\Utils;

class Arr {
    /***
     * Select part of array
     * @param array $array
     * @param $part
     * @return array
     */
    public static function select(array $array, $part) : array
    {
        $result = [];

        foreach($array as $item)
        {
            $result[] = $item[$part];
        }

        return $result;
    }
}
