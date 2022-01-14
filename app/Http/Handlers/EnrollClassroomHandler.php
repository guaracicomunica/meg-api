<?php

namespace App\Http\Handlers;

use App\Models\Activity;
use App\Models\Classroom;
use App\Models\ClassroomParticipant;
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

            $activities = Activity::all();

            DB::beginTransaction();

            ClassroomParticipant::assignParticipant($userId, $classroomId);

            foreach($activities as $activity)
            {
                $activity->assignToStudent($userId);
            }

            DB::commit();
        } catch(\Exception $ex)
        {
            DB::rollBack();
            throw $ex;
        }
    }
}
