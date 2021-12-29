<?php

namespace App\Http\Handlers\API;

use App\Models\Activity;
use App\Models\Post;
use Illuminate\Support\Facades\DB;

class CreateActivityHandler
{
    /**
     * @throws \Throwable
     */
    public static function handle(array $data)
    {
        try {
            DB::beginTransaction();

            $assignedPostData = [
                'name' => $data['name'],
                'body' => $data['body'],
                'is_private' => $data['is_private'],
                'disabled' => $data['disabled'],
                'classroom_id' => $data['classroom_id']
            ];

            $post = Post::create($assignedPostData);

            $assignedActivityData = [
                'deadline' => $data['deadline'] ?? null,
                'coins' => $data['coins'],
                'xp' => $data['coins'],
                'points' => $data['points'],
                'post_id' => $post->id
            ];

            Activity::create($assignedActivityData);

            DB::commit();
        } catch (\Throwable $ex)
        {
            DB::rollback();
            throw $ex;
        }
    }
}
