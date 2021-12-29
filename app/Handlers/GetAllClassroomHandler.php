<?php

namespace App\Handlers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GetAllClassroomHandler
{
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
            ->latest()
            ->paginate($request->per_page);
    }
}
