<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassroomParticipant extends Model
{
    use HasFactory;

    protected $table = 'users_classrooms';

    protected $fillable = [
        'user_id',
        'classroom_id',
    ];

    public static function assignCreatorAsFirstParticipantOfClassroom($creatorId, $classroomId)
    {
        self::firstOrCreate([
            'user_id' => $creatorId,
            'classroom_id' => $classroomId,
        ]);
    }
}
