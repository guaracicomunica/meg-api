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
        if(!filter_var($value, FILTER_VALIDATE_URL)){
            return Storage::disk('s3')->temporaryUrl($value, now()->addDay());
        } else {
            return $value;
        }
    }

    /***
     * @param $path
     * @return void
     */
    public static function delete($path)
    {
        Storage::disk('s3')->delete($path);
    }
}
