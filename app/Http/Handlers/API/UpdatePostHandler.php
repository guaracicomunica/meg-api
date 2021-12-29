<?php

namespace App\Http\Handlers\API;

class UpdatePostHandler
{
    public static function handler(int $id, array $data)
    {
        dd('oi');
        Post::findOrFail($id)->update($data);
    }
}
