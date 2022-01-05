<?php

namespace App\Http\Handlers;
use App\Models\Classroom;
use Illuminate\Database\Eloquent\Builder;

class GetByIdClassroomHandler
{
    public static function handle(int $classroom_id)
    {
        return Classroom::where('id',$classroom_id)
            ->with([
            'posts' => function($query) {
                $query->where('post_type_id', 1); //of type post => 1
            },
           'activities' => function($query) {
               $query->where('post_type_id', 2); //of type activity => 2
           },   
            'teacher' => function($query) {
                $query->whereHas('roles', function(Builder $query){
                    $query->where('roles.id', 2);
                })->select('name');
            }
        ])->first();
    }
}
