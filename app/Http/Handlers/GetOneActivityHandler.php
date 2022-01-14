<?php

namespace App\Http\Handlers;

use App\Models\Activity;

class GetOneActivityHandler
{
    public static function handle(int $id)
    {
        return Activity::with([
                'post',
                'post.attachments',
                'post.comments' => function($query){
                    $query->where('is_private', false);
                }])->findOrFail($id);
    }
}
