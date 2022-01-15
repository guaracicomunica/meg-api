<?php

namespace App\Http\Handlers;

use App\Jobs\MailJob;
use App\Mail\ClassroomInvitationMail;
use App\Models\Classroom;
use App\Models\ClassroomParticipant;
use App\Models\Level;
use App\Models\Skill;
use App\Utils\UniqueCode;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;
use function dispatch;

class ManageClassroomHandler
{
    /**
     * @throws Throwable
     */
    public static function handle(array $data) : Classroom
    {
        $assignedValues = [
            'id' => isset($data['id']) ?? $data['id'],
            'name'=> $data['name'],
            'nickname'=> $data['nickname'],
            'code' => UniqueCode::generate(),
            'status' => !$data['is_draft'],
            'creator_id' => Auth::user()->id,
        ];

        try {
            DB::beginTransaction();

            $classroom = null;

            if(isset($data['id']))
            {
                $classroom = Classroom::where('id', $data['id'])->first();
            }

            if($classroom == null)
            {
                $classroom = Classroom::create($assignedValues);

                if(isset($data['file'])) {
                    $classroom->banner = $classroom->uploadFile($data['file'], "banners", $classroom->id);
                    $classroom->save();
                };
            } else {
                if(isset($data['file'])) {
                    $classroom->banner = $classroom->uploadFile($data['file'], "banners", $classroom->id);
                };
                $classroom->updateSafely($assignedValues);
            }

            ClassroomParticipant::assignParticipant(
                $classroom->creator_id,
                $classroom->id
            );

            AlterClassroomGamificationRatesHandler::handle(
                Level::class,
                $data['levels'] ?? [],
                $classroom->id,
                (bool) $data['is_draft']
            );

            AlterClassroomGamificationRatesHandler::handle(
                Skill::class,
                $data['skills'] ?? [],
                $classroom->id,
                (bool) $data['is_draft']
            );

            if(isset($data['partners']))
            {
                $job = new MailJob($data['partners'], new ClassroomInvitationMail($classroom));
                dispatch($job);
            }

            DB::commit();

            return $classroom;
        } catch (Throwable $ex)
        {
            DB::rollback();
            throw $ex;
        }
    }
}
