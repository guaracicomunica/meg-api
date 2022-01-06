<?php

namespace App\Http\Handlers;

use App\Http\Requests\GetAllPostsRequest;
use App\Models\Post;

class GetAllPostsHandler
{
    public static function handle(GetAllPostsRequest $request)
    {
        return Post::with([
            'comments',
            'activity'
        ])->where('classroom_id', $request->classroom_id)->paginate();
    }
}
