<?php

namespace App\Handlers;

use App\Models\Classroom;
use App\Models\ClassroomParticipant;
use Illuminate\Support\Facades\Auth;

class EnrollClassroomHandler
{
    public static function handle(array $data)
    {
        $userId = Auth::user()->id;
        $classroomId = Classroom::where('code', $data['code'])->first()->id;
        ClassroomParticipant::assignParticipant($userId, $classroomId);
    }
}
