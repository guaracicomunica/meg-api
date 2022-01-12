<?php

namespace App\Http\Handlers;

use App\Http\Requests\GradeStudentsActivityRequest;
use App\Models\Activity;
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
                        $xp = $activity->calcXpFromStudentGrade($student['grade']);
                        $coins = $activity->calcCoinsFromStudentGrade($student['grade']);

                        $data = [
                            'points' => $student['grade'],
                            'xp' => $xp,
                            'coins' => $coins,
                        ];

                        UserActivity::
                            where('user_id', $student['id'])
                            ->where('activity_id', $activity->id)
                            ->update($data);

                        $act = UserStatusGamefication::firstOrCreate(
                            ['user_id' => $student['id']],
                            ['points' => $xp, 'coins' => $coins, 'user_id' => $student['id']]
                        );

                        if($act->xp != $xp)
                        {
                            $act->xp += $xp;
                            $act->save();
                        }

                        if($act->coins != $coins)
                        {
                            $act->coins += $coins;
                            $act->save();
                        }
                    }
                });
            DB::commit();
        } catch(\Exception $ex)
        {
            DB::rollBack();
            throw $ex;
        }
    }
}
