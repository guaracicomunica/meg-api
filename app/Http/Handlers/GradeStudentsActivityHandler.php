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
                            'scored_at' => Carbon::now()
                        ];

                        $userActivity = UserActivity::
                            where('user_id', $student['id'])
                            ->where('activity_id', $activity->id)->firstOrFail();

                        $userActivity->update($data);

                        $gamification = UserStatusGamefication::firstOrCreate(
                            ['user_id' => $student['id']],
                            ['coins' => 0, 'user_id' => $student['id']]
                        );

                        if($userActivity->scored_at != null)
                        {
                            $gamification->coins += $coins;
                        } else {
                            $gamification->coins = $coins;
                        }

                        $gamification->save();

                        $classroom = $activity->post->classroom;

                        $participant = ClassroomParticipant::
                            where('user_id', $student['id'])
                            ->where('classroom_id', $classroom->id)
                            ->firstOrFail();

                        $participant->levelUp($classroom, $userActivity);
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
