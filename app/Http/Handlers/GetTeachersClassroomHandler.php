<?php

namespace App\Http\Handlers;

use App\Http\Resources\TeacherResource;
use App\Models\Classroom;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class GetTeachersClassroomHandler
{
    public static function handle(int $id)
    {
        if(!Auth::user()->isMemberOfClassroom($id)) {
            throw new AccessDeniedHttpException();
        }
        $classroom = Classroom::findOrFail($id);
        $students = $classroom->teachers()->paginate();
        return TeacherResource::collection($students)->response()->getData();
    }
}
