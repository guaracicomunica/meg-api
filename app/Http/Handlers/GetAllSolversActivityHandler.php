<?php

namespace App\Http\Handlers;

use App\Http\Requests\GetAllSolversActivityRequest;
use App\Models\User;
use App\Models\UserActivity;

class GetAllSolversActivityHandler
{
    public static function handle(GetAllSolversActivityRequest $request)
    {
        return User::whereHas('activities', function($query) use ($request){
            $query->where('activities.id', $request->get('activity_id'));
            $query->whereNotNull('delivered_at');
        })->paginate();
    }
}
