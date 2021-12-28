<?php

namespace App\Handlers;

use App\Utils\Arr;
use Illuminate\Database\Eloquent\Model;

class AssignClassroomGamificationRateHandler {
    /***
     * It associates a classroom to a gamification rate at moment of its creation.
     * @param string $entity
     * @param array $resources - levels or skills
     * @param int $classroomId
     * @param bool $isDraft
     * @return void
     */
    public static function handle(string $entity,array $resources, int $classroomId, bool $isDraft)
    {
        self::updateIfNew($entity, $resources, $classroomId);

        if($isDraft)
        {
            self::removeIfUnused($entity, $resources);
        }
    }

    private static function updateIfNew(string $entity, array $resources, int $classroomId)
    {
        foreach($resources as $level)
        {
            $level = array_merge($level, ['classroom_id' => $classroomId]);
            $match = ['name' => $level['name'], 'classroom_id' => $classroomId];
            $entity::updateOrCreate($match, $level);
        }
    }

    private static function removeIfUnused(string $entity, array $resources)
    {
        $names = Arr::select($resources, 'name');
        $entity::whereNotIn('name', $names)->delete();
    }
}
