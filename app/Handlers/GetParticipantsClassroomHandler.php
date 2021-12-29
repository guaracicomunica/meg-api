<?php

namespace App\Handlers;

use App\Models\Classroom;

class GetParticipantsClassroomHandler
{
    public static function handle(int $id)
    {
        $classroom = Classroom::findOrFail($id);
        return $classroom->participants()->get();
    }

}
