<?php

namespace App\Utils;

use Illuminate\Support\Facades\Storage;

class File
{
    public static function saveAs($folder, $file, $name)
    {
        $path = null;

        if($file != null && $file->isValid()) {
            $path = Storage::disk('s3')->put("{$folder}/{$name}", $file);
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
            return Storage::disk('s3')->temporaryUrl($value, now()->addDay());
        } else {
            return $value;
        }
    }
}
