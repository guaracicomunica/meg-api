<?php

namespace App\Http\Handlers;

use App\Http\Resources\ParticipantResource;
use App\Models\Classroom;

class GetParticipantsClassroomHandler
{
    public static function handle(int $id)
    {
        $classroom = Classroom::findOrFail($id);
        $participants = $classroom->participants()->get();

        return ParticipantResource::collection($participants);
    }

}
