<?php

namespace App\Http\Handlers;

use App\Http\Requests\GetAllActivityRequest;
use App\Models\Activity;

class GetAllActivityHandler
{
    public static function handle()
    {
        return Activity::with([
                'post',
                'post.attachments',
                'post.comments' => function($query){
                    $query->where('is_private', false);
                }
            ])->paginate();
    }
}
