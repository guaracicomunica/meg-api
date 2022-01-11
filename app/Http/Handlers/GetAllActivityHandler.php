<?php

namespace App\Http\Handlers;

use App\Models\Activity;

class GetAllActivityHandler
{
    public static function handle()
    {
        return Activity::with(
            ['post', 'post.attachments', 'post.comments']
        )->paginate();
    }
}
