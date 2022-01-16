<?php

namespace App\Http\Handlers;

use App\Models\Classroom;
use App\Models\ClassroomParticipant;
use App\Models\ReportCard;
use Illuminate\Support\Facades\Auth;

class EnrollmentCancellationHandler
{
    public static function handle(array $data)
    {
        ClassroomParticipant::where('user_id', $data['user_id'])
            ->where('classroom_id', $data['classroom_id'])
            ->delete();

        ReportCard::exclude($data['user_id'], $data['classroom_id']);
    }
}
