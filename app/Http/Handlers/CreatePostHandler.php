<?php

namespace App\Http\Handlers;

use App\Models\Post;

class CreatePostHandler
{
    public static function handle(array $data)
    {
        Post::create($data);
    }
}
