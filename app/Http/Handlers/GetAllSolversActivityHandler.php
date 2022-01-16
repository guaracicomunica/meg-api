<?php

namespace App\Http\Handlers;

use App\Http\Requests\GetAllSolversActivityRequest;
use App\Http\Resources\SolverResource;
use App\Models\User;
use App\Models\UserActivity;

class GetAllSolversActivityHandler
{
    public static function handle(GetAllSolversActivityRequest $request)
    {
        $records = UserActivity::with([
            'user',
            'activity',
            'activity.post.classroom'
        ])
            ->where('activity_id', $request->get('activity_id'))
            ->paginate($request->get('per_page'));

        $totalDeliveredActivities = UserActivity::where('activity_id', $request->get('activity_id'))->whereNotNull('delivered_at')->count();
        $totalAssignments = UserActivity::where('activity_id', $request->get('activity_id'))->count();

        return [
            'totalDeliveredActivities' => $totalDeliveredActivities,
            'totalAssignments' => $totalAssignments,
            'classroom' => $records->first()->activity->post->classroom->name,
            'deadline' => $records->first()->activity->deadline,
            'users' => SolverResource::collection($records)
        ];
    }
}
