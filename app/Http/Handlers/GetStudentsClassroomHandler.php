<?php

namespace App\Http\Handlers;

use App\Http\Resources\ParticipantResource;
use App\Http\Resources\StudentResource;
use App\Models\Classroom;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class GetStudentsClassroomHandler
{
    public static function handle(int $id)
    {
        if(!Auth::user()->isMemberOfClassroom($id)) {
           throw new AccessDeniedHttpException();
        }
        $classroom = Classroom::findOrFail($id);
        $students = $classroom->students()->with('activities','gamefication')->paginate();
        return StudentResource::collection($students)->response()->getData();
    }
}
