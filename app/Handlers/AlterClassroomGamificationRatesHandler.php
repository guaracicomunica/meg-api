<?php

namespace App\Handlers;

use App\Utils\Arr;

class AlterClassroomGamificationRatesHandler {
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

    /****
     * Create or update a set of levels and skills depending on
     * it already exists on database.
     * @param string $entity
     * @param array $resources
     * @param int $classroomId
     * @return void
     */
    private static function updateIfNew(string $entity, array $resources, int $classroomId)
    {
        foreach($resources as $resource)
        {
            $resource = array_merge($resource, ['classroom_id' => $classroomId]);
            $match = ['name' => $resource['name'], 'classroom_id' => $classroomId];
            $entity::updateOrCreate($match, $resource);
        }
    }

    /****
     * If a user removes a level or skill while is making a draft,
     * records on database must be deleted.
     * @param string $entity
     * @param array $resources
     * @return void
     */
    private static function removeIfUnused(string $entity, array $resources)
    {
        $names = Arr::select($resources, 'name');
        $entity::whereNotIn('name', $names)->delete();
    }
}
