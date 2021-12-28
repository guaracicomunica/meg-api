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
}
