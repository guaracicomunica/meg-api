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

    public static function findByKeys(int $studentId, int $classroomId)
    {
        return self::where('user_id', $studentId)
            ->where('classroom_id', $classroomId)
            ->firstOrFail();
    }

    public function levelUp($classroom, $userActivity, $previousRecord)
    {
        $newLevel = null;

        foreach($classroom->levels as $levelOfClassroom)
        {
            if($levelOfClassroom->xp <= $userActivity->xp) {
                $newLevel = $levelOfClassroom->id;
            }
        }

        /*
         * se houver nota previamente dada a usuário, remova a quantidade de xp adicionado ao seu perfil na turma
         * e atualize-o com base na nova nota.
         * **/
        if($userActivity->scored_at != null)
        {
            $this->xp += ($this->xp - $previousRecord->xp) + $userActivity->xp;
        } else {
            $this->xp += $userActivity->xp;
        }

        if($newLevel != $this->level_id)
        {
            $this->level_id = $newLevel;
        }

        $this->save();
    }
}
