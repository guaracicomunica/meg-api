<?php

namespace App\Http\Handlers;

use App\Http\Resources\ParticipantResource;
use App\Http\Resources\StudentResource;
use App\Models\Classroom;

class GetStudentsClassroomHandler
{
    public static function handle(int $id)
    {
        $classroom = Classroom::findOrFail($id);
        $students = $classroom->students()->with('gamefication')->paginate();
        return StudentResource::collection($students)->response()->getData();
    }
}
