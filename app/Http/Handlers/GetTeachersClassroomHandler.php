<?php

namespace App\Http\Handlers;

use App\Http\Resources\TeacherResource;
use App\Models\Classroom;

class GetTeachersClassroomHandler
{
    public static function handle(int $id)
    {
        $classroom = Classroom::findOrFail($id);
        $students = $classroom->teachers()->paginate();
        return TeacherResource::collection($students)->response()->getData();
    }
}
