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
                'post.comments' => function($query){
                    $query->where('is_private', false);
                }])->findOrFail($id);

        return new ActivityResource($activity);
    }
}
