<?php

namespace App\Utils;

class File
{
    public static function saveAs($folder, $file, $name)
    {
        $path = null;

        if($file != null && $file->isValid())
        {
            $path = $file->storeAs($folder, "{$name}.{$file->extension()}");
        }

        return $path;
    }

    /**
     * @param $value
     * @return mixed|string
     */
    public static function formatLink($value)
    {
        if(!str_contains($value, "http")){
            return env('APP_URL').'/'.StringUtil::str_replace_limit('public/', 'storage/',$value, 1);
        } else {
            return $value;
        }
    }
}
