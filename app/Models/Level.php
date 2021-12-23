<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Integer;

class Level extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'xp',
        'classroom_id'
    ];

    public static function createAndAssignToClassroom(array $levels, int $classroomId)
    {
        if($levels)
        {
            foreach($levels as $level)
            {
                $level = array_merge($level, ['classroom_id' => $classroomId]);
                $match = ['name' => $level['name'], 'classroom_id' => $classroomId];
                self::updateOrCreate($match, $level);
            }
        }
    }
}
