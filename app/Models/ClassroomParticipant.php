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
}
