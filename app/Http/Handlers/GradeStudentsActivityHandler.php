<?php

namespace App\Http\Handlers;

use App\Http\Requests\GradeStudentsActivityRequest;
use App\Models\Activity;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class GradeStudentsActivityHandler
{
    public static function handle(GradeStudentsActivityRequest $request)
    {
        $activity = Activity::findOrFail($request->get('activity_id'));

        $studentIds = array_map(function($item){
            return $item['id'];
        }, $request->get('users'));

        DB::table('users_activities')
            ->where('activity_id', $request->get('activity_id'))
            ->whereNotNull('delivered_at')
            ->whereNull('scored_at')
            ->whereIn('user_id', $studentIds)
            ->chunkById(100, function() use ($request, $activity) {
                foreach($request->get('users') as $student)
                {
                    DB::table('users_activities')
                        ->where('user_id', $student['id'])
                        ->update([
                            'points' => $student['grade'],
                            'xp' => $activity->calcXpFromStudentGrade($student['grade']),
                            'coins' => $activity->calcCoinsFromStudentGrade($student['grade']),
                            'scored_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ]);
                }
            });
    }

    public static function grade($students, $activity)
    {

    }
}
