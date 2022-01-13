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

    /***
     * Ao modificar a nota do aluno, é recalculada a quantidade de xp.
     * Seu status da turma deve receber a quantidade e excluir a última ganha
     * pela mesma atividade, pois já não vale mais após retificação.
     * @param $newXp
     * @param $oldXp
     * @return int
     */
    public function recalculateXp(
        float $newPoints,
        int $newXp,
        float $oldPoints,
        int $oldXp
    ): int
    {
        if($newPoints > $oldPoints)
        {
            return ($this->xp - $oldXp) + $newXp;
        } else {
            return $this->xp - ($newXp - $oldXp);
        }
    }

    /***
     * Seta novo nível para aluno caso tenha obtido quantidade de xp suficiente.
     * @param $levelsOfClassroom
     * @param $currentStudentXp
     * @return void
     */
    public function tryLevelUp($levelsOfClassroom, $currentStudentXp)
    {
        $newLevel = null;

        foreach($levelsOfClassroom as $levelOfClassroom)
        {
            if($levelOfClassroom->xp <= $currentStudentXp) {
                $newLevel = $levelOfClassroom->id;
            }
        }

        if($newLevel != $this->level_id)
        {
            $this->level_id = $newLevel;
        }
    }
}
