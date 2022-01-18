<?php

namespace App\Http\Handlers;

use App\Models\Activity;
use App\Models\Post;
use App\Models\PostAttachment;
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

            //cria post
            $post = Post::create(
                array_merge($assignedPostData, ['creator_id' => Auth::user()->id])
            );

            $assignedActivityData = [
                'deadline' => $data['deadline'] ?? null,
                'coins' => $data['coins'],
                'xp' => $data['coins'],
                'points' => $data['points'],
                'post_id' => $post->id,
                'topic_id' => $data['topic_id'],
                'unit_id' => $data['unit_id'],
            ];

            //cria atividade
            $activity = Activity::create($assignedActivityData);

            //atribui atividade aos alunos da turma
            $activity->assignToAllStudents($assignedPostData['classroom_id']);

            //upload de arquivos em anexo
            if(isset($data['attachments']))
                foreach ($data['attachments'] as $file)
                {
                    $postAttachment = new PostAttachment();
                    $postAttachment->uploadAttachments($file, $post->id);
                }

            if(isset($data['links']))
            {
                foreach ($data['links'] as $link)
                {
                    PostAttachment::firstOrCreate([
                        'path' => $link,
                        'post_id' => $post->id,
                        'is_external_link' => true
                    ]);
                }
            }

            DB::commit();
        } catch (\Throwable $ex)
        {
            DB::rollback();
            throw $ex;
        }
    }
}
