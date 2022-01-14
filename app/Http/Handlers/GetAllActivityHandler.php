<?php

namespace App\Http\Handlers;

use Illuminate\Http\Request;
use App\Http\Requests\GetAllActivityRequest;
use App\Models\Activity;

class GetAllActivityHandler
{
    public static function handle(Request $request)
    {
        return Activity::with([
                'post',
                'post.attachments',
                'post.comments' => function($query){
                    $query->where('is_private', false);
                }
            ])->paginate($request->per_page);
    }
}
