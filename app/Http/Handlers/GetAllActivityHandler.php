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
                'post' => function($query) use ($request) {
                    if(Auth::user()->isStudent())
                    {
                        $query->where('disabled', false);
                    }
                    $query->when(
                        $request->get('classroom_id'),
                        function($query, $classroomId){
                            $query->where('classroom_id', $classroomId);
                    });
                },
                'post.attachments',
                'post.comments' => function($query){
                    $query->where('is_private', false);
                }
            ])->when($request->get('topic_id'),
                function($query, $topicId){
                    $query->where('topic_id', $topicId);
                })
            ->paginate($request->per_page);
    }
}
