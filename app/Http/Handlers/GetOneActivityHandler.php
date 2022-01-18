<?php

namespace App\Http\Handlers;

use App\Http\Resources\ActivityStudentResource;
use App\Http\Resources\ActivityTeacherResource;
use App\Http\Resources\PostResource;
use App\Models\Activity;
use App\Models\Post;
use App\Models\UserActivity;
use Illuminate\Support\Facades\Auth;

class GetOneActivityHandler
{
    public static function handle(int $id)
    {
        $result = null;

        if(Auth::user()->isStudent())
        {
            $result = self::getActivityWithStudentView($id);
        }

        if(Auth::user()->isTeacher())
        {
            $result = self::getActivityWithTeacherView($id);
        }

        return $result;
    }

    private static function getActivityWithStudentView(int $id): ActivityStudentResource
    {
        $activity = Auth::user()->activities()
            ->whereHas('post', function($query) {
                $query->where('disabled', false);
            })->where('activities.id', $id)
            ->firstOrFail();
        return new ActivityStudentResource($activity);
    }

    private static function getActivityWithTeacherView(int $id): ActivityTeacherResource
    {
        $activity = Activity::with([
            'post',
            'post.attachments',
            'post.comments'  => function($query) {
                $query->whereNull('comment_id');
            }
        ])->findOrFail($id);

        return new ActivityTeacherResource($activity);
    }
}
