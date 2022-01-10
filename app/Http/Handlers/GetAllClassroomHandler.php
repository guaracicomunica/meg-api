<?php

namespace App\Http\Handlers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GetAllClassroomHandler
{
    /***
     * Get all classrooms with their levels, skills and partners (participants who are teachers)
     * @param Request $request
     * @return mixed
     */
    public static function handle(Request $request)
    {
        return Auth::user()
            ->classes()
            ->with([
                'levels',
                'skills',
                'participants' => function($query) {
                    $query->whereHas('roles', function(Builder $query){
                        $query->where('roles.id', 2);
                    })->select('email');
                }
            ])
            ->orderBy('status', 'desc')
            ->paginate($request->per_page);
    }
}
