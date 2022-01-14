<?php

namespace App\Http\Handlers;

use App\Http\Requests\FillReportCardRequest;
use App\Models\ReportCard;
use App\Models\UserActivity;
use Exception;
use Illuminate\Support\Facades\DB;

class FillReportCardHandler
{

    /**
     * @throws Exception
     */
    public static function handle(FillReportCardRequest $request)
    {
        try {
            DB::beginTransaction();

            $records = UserActivity::
                        whereHas(
                            'activity.post.classroom',
                            function($query) use ($request){
                                $query->where('classrooms.id', $request->get('classroom_id'));
                        })
                        ->whereHas(
                            'activity',
                            function($query) use ($request){
                                $query->where('unit_id', $request->get('unit_id'));
                            }
                        )
                        ->whereNotNull('delivered_at')
                        ->whereNotNull('scored_at')
                        ->whereIn('user_id', $request->get('users'))
                        ->get();

            foreach($request->get('users') as $userId)
            {
                $avg = $records->where('user_id', $userId)->avg('points');

                $data = [
                    'user_id' => $userId,
                    'unit_id' => $request->get('unit_id'),
                    'classroom_id' => $request->get('classroom_id')
                ];

                ReportCard::updateOrCreate($data, ['average' => $avg]);
            }

            DB::commit();
        } catch(Exception $ex)
        {
            DB::rollBack();
            throw $ex;
        }
    }
}
