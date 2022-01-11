<?php

namespace App\Http\Handlers;

use App\Models\Activity;

class GetOneActivityHandler
{
    public static function handle(int $id)
    {
        return Activity::with(
            ['post', 'post.comments', 'post.attachments']
        )->findOrFail($id);
    }
}
