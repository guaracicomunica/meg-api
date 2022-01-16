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
        $users = UserActivity::with([
            'user',
            'activity',
            'activity.attachments'
        ])
            ->where('activity_id', $request->get('activity_id'))
            ->whereNotNull('delivered_at')
            ->paginate();

        return SolverResource::collection($users)->response()->getData();
    }
}
