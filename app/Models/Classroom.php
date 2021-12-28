<?php

namespace App\Models;

use App\Handlers\AssignClassroomGamificationRateHandler;
use App\Jobs\MailJob;
use App\Mail\ClassroomInvitationMail;
use App\Utils\UniqueCode;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Throwable;

/**
 * @method static create(array $assignedValues)
 * @method static where(string $string, mixed $id)
 */
class Classroom extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'nickname',
        'status',
        'code',
        'banner',
        'creator_id'
    ];

    public function levels(): HasMany
    {
        return $this->hasMany(Level::class, 'classroom_id');
    }

    public function skills(): HasMany
    {
        return $this->hasMany(Skill::class, 'classroom_id');
    }

    /**
     * @throws Throwable
     */
    public static function createClassroom(array $data) : Classroom
    {
        $assignedValues = [
            'id' => $data['id'],
            'name'=> $data['name'],
            'nickname'=> $data['nickname'],
            'code' => UniqueCode::generate(),
            'status' => !$data['is_draft'],
            'banner' => null,
            'creator_id' => Auth::user()->id,
        ];

        try {
            DB::beginTransaction();

            $classroom = self::where('id', $data['id'])->first();

            if($classroom == null)
            {
                $classroom = self::create($assignedValues);
                ClassroomParticipant::assignCreatorAsFirstParticipantOfClassroom(
                    $classroom->creator_id,
                    $classroom->id
                );
            } else {
                $classroom->updateSafely($assignedValues);
            }

            AssignClassroomGamificationRateHandler::handle(Level::class, $data['levels'] ?? [], $classroom->id, (bool) $data['is_draft']);

            AssignClassroomGamificationRateHandler::handle(Skill::class, $data['skills'] ?? [], $classroom->id, (bool) $data['is_draft']);

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

    /**
     * Update classroom, ignoring fields such as code (unique)
     * and creator_id, which must be immutable.
     * @returns boolean
     */
    public function updateSafely(array $items): bool
    {
        unset($items['code']);
        unset($items['creator_id']);
        return $this->fill($items)->save();
    }
}
