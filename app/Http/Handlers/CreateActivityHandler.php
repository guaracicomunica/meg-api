<?php

namespace App\Http\Handlers;

use App\Models\Activity;
use App\Models\Post;
use App\Models\PostFile;
use Illuminate\Support\Facades\Auth;
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
                'is_private' => 0, //toda atividade é pública para membros da turma
                'disabled' => $data['disabled'],
                'classroom_id' => $data['classroom_id']
            ];

            $post = Post::create(
                array_merge($assignedPostData, ['creator_id' => Auth::user()->id])
            );

            $assignedActivityData = [
                'deadline' => $data['deadline'] ?? null,
                'coins' => $data['coins'],
                'xp' => $data['coins'],
                'points' => $data['points'],
                'post_id' => $post->id
            ];

            Activity::create($assignedActivityData);

            //uplaod de arquivos em anexo
            if(isset($data['attachments']))
                foreach ($data['attachments'] as $file)
                {
                    $postFile = new PostFile();
                    $postFile->uploadAttachments($file, $post->id);
                }

            DB::commit();
        } catch (\Throwable $ex)
        {
            DB::rollback();
            throw $ex;
        }
    }
}
