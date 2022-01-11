<?php

namespace App\Http\Handlers;

use App\Models\Topic;
use Illuminate\Http\Request;

class GetAllTopicsClassroomHandler
{

    public static function handle(Request $request)
    {
        return Topic::where('classroom_id', $request->classroom_id)->paginate();
    }
}
