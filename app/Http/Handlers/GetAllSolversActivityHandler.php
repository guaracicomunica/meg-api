<?php

namespace App\Http\Handlers;

use App\Http\Requests\GetAllSolversActivityRequest;
use App\Http\Resources\SolverResource;
use App\Models\UserActivity;

class GetAllSolversActivityHandler
{
    /**
     * @throws ValidationException
     */
    public static function handle(GetAllSolversActivityRequest $request)
    {
        $records = UserActivity::with([
            'user',
            'deliveredFiles',
            'activity'
        ])
            ->where('activity_id', $request->get('activity_id'))
            ->paginate($request->get('per_page'));

        $totalDeliveredActivities = UserActivity::where('activity_id', $request->get('activity_id'))->whereNotNull('delivered_at')->count();
        $totalAssignments = UserActivity::where('activity_id', $request->get('activity_id'))->count();

        if($totalAssignments == 0)
        {
            return [
                'totalDeliveredActivities' => $totalDeliveredActivities,
                'totalAssignments' => $totalAssignments,
                'classroom' => null,
                'deadline' => null,
                'users' => []
            ];
        } else {
            return [
                'totalDeliveredActivities' => $totalDeliveredActivities,
                'totalAssignments' => $totalAssignments,
                'classroom' => $records->first()->activity->post->classroom->name,
                'deadline' => $records->first()->activity->deadline,
                'users' => SolverResource::collection($records)
            ];
        }
    }
}
