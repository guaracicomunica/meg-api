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
        $result = [];

        if($levels)
        {
            foreach($levels as $level)
            {
                $level = [
                    'name' => $level['name'],
                    'xp'=> $level['xp'],
                    'classroom_id' => $classroomId
                ];

                $result = array_merge($result, $level);
            }

            self::insert($result);
        }
    }
}
