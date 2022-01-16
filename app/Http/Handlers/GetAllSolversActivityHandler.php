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
            'activity.attachments'
        ])
            ->where('activity_id', $request->get('activity_id'))
            ->whereNotNull('delivered_at')
            ->paginate($request->get('per_page'));

        $totalDeliveredActivities = count($records);
        $totalAssignments = UserActivity::where('activity_id', $request->get('activity_id'))->count();

        return [
            'totalDeliveredActivities' => $totalDeliveredActivities,
            'totalAssignments' => $totalAssignments,
            'users' => SolverResource::collection($records)
        ];
    }
}
