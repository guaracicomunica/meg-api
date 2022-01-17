<?php

namespace App\Http\Handlers;

use App\Http\Requests\UpdateActivityRequest;
use App\Models\Activity;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class UpdateActivityHandler
{
    /**
     * @throws ValidationException
     * @throws \Exception
     */
    public static function handle(int $id, UpdateActivityRequest $request)
    {
        $activity = Activity::with('post')->findOrFail($id);

        if($activity->atLeastOneStudentScored() && self::requestContainsReservedFields($request))
        {
            throw ValidationException::withMessages(["activity" =>  "Campos reservados da atividade não podem ser alterados após pontuar um dos alunos"]);
        }

        try {
            DB::beginTransaction();

            $activityData = $request->only(['points', 'xp', 'coins', 'deadline', 'topic_id', 'unit_id']);

            if(count($activityData))
            {
                $activity->update($activityData);
            }

            $postData = $request->only(['name', 'body', 'disabled']);

            if(count($postData))
            {
                $activity->post()->update($postData);
            }

            DB::commit();
        } catch(\Exception $ex)
        {
            DB::rollBack();
            throw $ex;
        }
    }

    private static function requestContainsReservedFields(UpdateActivityRequest $request)
    {
        $result = false;

        foreach(Activity::reservedWords as $word)
        {
            if($request->exists($word))
            {
                $result = true;
            }
        }

        return $result;
    }
}
