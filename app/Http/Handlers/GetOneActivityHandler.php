<?php

namespace App\Http\Handlers;

use App\Http\Resources\ActivityResource;
use App\Http\Resources\PostResource;
use App\Models\Activity;
use App\Models\Post;

class GetOneActivityHandler
{
    public static function handle(int $id)
    {

        $activity = Activity::with([
                'post',
                'post.attachments',
                'post.comments'
                ])->findOrFail($id);

        return new ActivityResource($activity);
    }
}
