<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static firstOrCreate(array $array)
 */
class ClassroomParticipant extends Model
{
    use HasFactory;

    protected $table = 'users_classrooms';

    protected $fillable = [
        'user_id',
        'classroom_id',
        'xp',
        'level_id'
    ];

    /***
     * It turns a user into a classroom participant
     * @param $creatorId
     * @param $classroomId
     * @return void
     */
    public static function assignParticipant($creatorId, $classroomId)
    {
        self::firstOrCreate([
            'user_id' => $creatorId,
            'classroom_id' => $classroomId,
        ]);
    }

    public function levelUp($classroom, $userActivity)
    {
        $newLevel = null;

        foreach($classroom->levels as $levelOfClassroom)
        {
            if($levelOfClassroom->xp <= $userActivity->xp) {
                $newLevel = $levelOfClassroom->id;
            }
        }

        if($userActivity->scored_at != null)
        {
            $this->xp += $userActivity->xp;
        } else {
            $this->xp = $userActivity->xp;
        }

        if($newLevel != $this->level_id)
        {
            $this->level_id = $newLevel;
        }

        $this->save();
    }
}
