<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'coins',
        'classroom_id'
    ];

    /**
     * It creates a skill related to a classroom
     */
    public static function createAndAssignToClassroom(array $skills, int $classroomId)
    {
        if($skills)
        {
            foreach($skills as $skill)
            {
                $skill = array_merge($skill, ['classroom_id' => $classroomId]);
                $match = ['name' => $skill['name'], 'classroom_id' => $classroomId];
                self::updateOrCreate($match, $skill);
            }
        }
    }
}
