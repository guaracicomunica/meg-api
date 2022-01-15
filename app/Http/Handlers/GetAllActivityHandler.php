<?php

namespace App\Http\Handlers;

use Illuminate\Http\Request;
use App\Http\Requests\GetAllActivityRequest;
use App\Models\Activity;
use Illuminate\Support\Facades\Auth;

class GetAllActivityHandler
{
    public static function handle(Request $request)
    {
        return Activity::with([
                'post' => function($query){
                    if(Auth::user()->isStudent())
                    {
                        $query->where('disabled', false);
                    }
                },
                'post.attachments',
                'post.comments' => function($query){
                    $query->where('is_private', false);
                }
            ])
            ->paginate($request->per_page);
    }
}
