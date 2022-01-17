<?php

namespace App\Http\Handlers;

use App\Models\Activity;
use App\Models\Classroom;
use App\Models\ClassroomParticipant;
use App\Models\ReportCard;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EnrollClassroomHandler
{
    /**
     * @throws \Exception
     */
    public static function handle(array $data)
    {
        try {
            $userId = Auth::user()->id;

            $classroomId = Classroom::where('code', $data['code'])->first()->id;

            DB::beginTransaction();

            ClassroomParticipant::assignParticipant($userId, $classroomId);

            if(Auth::user()->isStudent())
            {
                $activities = Activity::all();

                foreach($activities as $activity)
                {
                    $activity->assignToStudent($userId);
                }
                ReportCard::createDefaultForStudent($userId, $classroomId);
            }

            DB::commit();
        } catch(\Exception $ex)
        {
            DB::rollBack();
            throw $ex;
        }
    }
}
