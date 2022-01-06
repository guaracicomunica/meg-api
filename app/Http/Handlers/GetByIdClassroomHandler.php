<?php

namespace App\Http\Handlers;
use App\Http\Resources\ClassroomResource;
use App\Models\Classroom;

class GetByIdClassroomHandler
{
    public static function handle(int $classroom_id): ClassroomResource
    {
        $classroom = Classroom::with(['creator'])->findOrFail($classroom_id);
        return new ClassroomResource($classroom);
    }
}
