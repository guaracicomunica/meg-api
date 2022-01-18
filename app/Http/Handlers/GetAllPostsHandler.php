<?php

namespace App\Http\Handlers;

use App\Http\Requests\GetAllPostsRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class GetAllPostsHandler
{
    public static function handle(GetAllPostsRequest $request)
    {
        $posts = Post::with([
            'comments',
            'activity',
            'attachments'
        ])->whereHas('activity.post', function($query) {
                if(Auth::user()->isStudent())
                {
                    $query->where('disabled', false);
                }
            })
            ->where('classroom_id', $request->classroom_id)
            ->orderByDesc('created_at')
            ->paginate($request->per_page);

        return PostResource::collection($posts)->response()->getData();
    }
}
