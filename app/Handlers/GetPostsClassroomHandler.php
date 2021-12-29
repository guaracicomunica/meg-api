<?php

namespace App\Handlers;


use App\Models\Classroom;
use Illuminate\Http\Request;

class GetPostsClassroomHandler
{

    public static function handle(int $id, Request $request)
    {
        return Classroom::findOrFail($id)->posts()->latest()->paginate($request->per_page);
    }
}
