<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static whereNotIn(string $string, $levelsDiscartedByUser)
 */
class Level extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'xp',
        'classroom_id'
    ];

    public static function createAndAssignToClassroom(array $levels, int $classroomId, bool $isDraft)
    {
        if($levels)
        {
            foreach($levels as $level)
            {
                $level = array_merge($level, ['classroom_id' => $classroomId]);
                $match = ['name' => $level['name'], 'classroom_id' => $classroomId];
                self::updateOrCreate($match, $level);
            }

            if($isDraft)
            {
                $names = self::getNamesArray($levels);
                self::whereNotIn('name', $names)->delete();
            }
        }

    }

    private static function getNamesArray(array $levels) : array
    {
        $names = [];

        foreach($levels as $level)
        {
            $names[] = $level['name'];
        }

        return $names;
    }
}
