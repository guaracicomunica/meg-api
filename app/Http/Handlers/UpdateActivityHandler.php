<?php

namespace App\Http\Handlers;

use App\Http\Requests\UpdateActivityRequest;
use App\Models\Activity;
use App\Models\PostAttachment;
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

            //upload de arquivos em anexo
            $files = $request->only(['attachments']);

            if(isset($files))
                foreach ($files as $file)
                {
                    $postAttachment = new PostAttachment();
                    $postAttachment->uploadAttachments($file, $activity->post->id);
                }

            $links = $request->only(['links']);

            if(isset($links))
            {
                foreach ($links as $link)
                {
                    PostAttachment::firstOrCreate([
                        'path' => $link,
                        'post_id' => $activity->post->id,
                        'is_external_link' => true
                    ]);
                }
            }

            DB::commit();
        } catch(\Exception $ex)
        {
            DB::rollBack();
            throw $ex;
        }
    }

    /***
     * @param UpdateActivityRequest $request
     * @return bool
     */
    private static function requestContainsReservedFields(UpdateActivityRequest $request): bool
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
