<?php

namespace App\Http\Handlers;

use App\Http\Resources\ParticipantResource;
use App\Models\Classroom;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class GetParticipantsClassroomHandler
{
    public static function handle(int $id)
    {
        if(!Auth::user()->isMemberOfClassroom($id)) {
            throw new AccessDeniedHttpException();
        }
        $classroom = Classroom::findOrFail($id);
        $participants = $classroom->participants()->get();
        return ParticipantResource::collection($participants);
    }

}
