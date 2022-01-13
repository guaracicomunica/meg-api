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
                        //map data from request
                        $student = self::getStudentFromRequest($request, $record);
                        $grade = $student['grade'];
                        $studentId = $student['id'];

                        //calculate xp and coins by grade
                        $xp = $activity->calcXpFromStudentGrade($grade);
                        $coins = $activity->calcCoinsFromStudentGrade($grade);

                        //find activity and update situation
                        $userActivity = UserActivity::findByKeys($student['id'], $activity->id);
                        $oldCoins = $userActivity->coins;
                        $userActivity->updateActivitySituation($grade, $xp, $coins);

                        //update student global status (quantity of coins)
                        $globalStatus = UserStatusGamefication::firstOrCreate(
                            ['user_id' => $studentId],
                            ['coins' => 0, 'user_id' => $studentId]
                        );
                        if($userActivity->alreadyScored())
                        {
                            $globalStatus->coins += $globalStatus->recalculateCoins($coins, $oldCoins);
                        } else {
                            $globalStatus->coins += $coins;
                        }
                        $globalStatus->save();

                        //update classroom status (student xp) and level up if must
                        $classroom = $activity->post->classroom;
                        $classroomStatus = ClassroomParticipant::findByKeys($studentId, $classroom->id);

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
