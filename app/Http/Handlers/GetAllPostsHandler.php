<?php

namespace App\Http\Handlers;

use App\Http\Requests\GetAllPostsRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;

class GetAllPostsHandler
{
    public static function handle(GetAllPostsRequest $request)
    {
        $posts = Post::with([
            'comments',
            'activity',
            'attachments'
        ])
            ->where('classroom_id', $request->classroom_id)
            ->orderByDesc('created_at')
            ->paginate($request->per_page);

        return PostResource::collection($posts)->response()->getData();
    }
}
