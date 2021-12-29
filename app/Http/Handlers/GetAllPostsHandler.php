<?php

namespace App\Http\Handlers;

use App\Http\Requests\GetAllPostsRequest;
use App\Models\Post;

class GetAllPostsHandler
{
    public static function handle(GetAllPostsRequest $request)
    {
        return Post::where('classroom_id', $request->classroom_id)->paginate();
    }
}
