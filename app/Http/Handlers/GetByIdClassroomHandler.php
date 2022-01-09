<?php

namespace App\Http\Handlers;
use App\Http\Resources\ClassroomResource;
use App\Models\Classroom;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class GetByIdClassroomHandler
{
    public static function handle(int $classroom_id): ClassroomResource
    {
        if(!Auth::user()->isMemberOfClassroom($classroom_id))
        {
            throw new AccessDeniedHttpException();
        }

        $classroom = Classroom::with(['creator'])->findOrFail($classroom_id);
        return new ClassroomResource($classroom);
    }
}
