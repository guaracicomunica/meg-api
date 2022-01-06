<?php

namespace App\Http\Handlers;
use App\Models\Classroom;
use Illuminate\Database\Eloquent\Builder;

class GetByIdClassroomHandler
{
    public static function handle(int $classroom_id)
    {
        return
            Classroom::with([
            'posts',
            'posts.comments',
            'posts.activity',
            'creator'
        ])->findOrFail($classroom_id);
    }
}
