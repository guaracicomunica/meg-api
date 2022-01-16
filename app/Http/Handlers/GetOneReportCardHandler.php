<?php

namespace App\Http\Handlers;

use App\Http\Requests\GetOneReportCardRequest;
use App\Http\Resources\ReportCardStudentResource;
use App\Http\Resources\ReportCardTeacherResource;
use App\Models\ClassroomParticipant;
use App\Models\User;
use App\Utils\RowQuery;
use Illuminate\Support\Facades\Auth;

class GetOneReportCardHandler
{
    public static function handle(GetOneReportCardRequest $request)
    {
        $user = ClassroomParticipant::with([
            'classroom',
            'user',
            'user.gamefication'
        ])-> where('user_id', Auth::user()->id)
                ->where('classroom_id', $request->get('classroom_id'))
                ->firstOrFail();

        return new ReportCardStudentResource($user);
    }
}
