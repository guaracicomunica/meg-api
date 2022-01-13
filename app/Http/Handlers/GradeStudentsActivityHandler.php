<?php

namespace App\Http\Handlers;

use App\Http\Requests\GradeStudentsActivityRequest;
use App\Models\Activity;
use App\Models\ClassroomParticipant;
use App\Models\UserActivity;
use App\Models\UserStatusGamefication;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class GradeStudentsActivityHandler
{
    /**
     * @throws \Exception
     */
    public static function handle(GradeStudentsActivityRequest $request)
    {
        try {
            DB::beginTransaction();

            $activity = Activity::with([
                'post.classroom',
                'post.classroom.levels'
            ])->findOrFail($request->get('activity_id'));

            $studentIds = array_map(function($item){
                return $item['id'];
            }, $request->get('users'));

            DB::table('users_activities')
                ->where('activity_id', $request->get('activity_id'))
                ->whereNotNull('delivered_at')
                ->whereIn('user_id', $studentIds)
                ->chunkById(100, function($records) use ($request, $activity) {
                    foreach($records as $record)
                    {
                        $student = self::getStudentFromRequest($request, $record);

                        $grade = $student['grade'];
                        $xp = $activity->calcXpFromStudentGrade($grade);
                        $coins = $activity->calcCoinsFromStudentGrade($grade);

                        $userActivity = UserActivity::findByKeys($student['id'], $activity->id);
                        $userActivity->updateActivitySituation($grade, $xp, $coins);
                    }
                });
            DB::commit();
        } catch(\Exception $ex)
        {
            DB::rollBack();
            throw $ex;
        }
    }

    private static function getStudentFromRequest($request, $record)
    {
        return array_filter(
            $request->get('users'),
            function($item) use ($record) {
                return $item['id'] == $record->user_id;
            }
        )[0];
    }
}
