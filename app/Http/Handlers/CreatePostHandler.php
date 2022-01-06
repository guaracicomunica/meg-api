<?php

namespace App\Http\Handlers;

use App\Models\Post;
use App\Models\PostFile;
use Illuminate\Support\Facades\DB;
use mysql_xdevapi\Exception;

class CreatePostHandler
{
    public static function handle(array $data)
    {
        try {

            DB::beginTransaction();

            $post = Post::create($data);

            if(isset($data['attachments']))
                foreach ($data['attachments'] as $file)
                {
                    $postFile = new PostFile();
                    $postFile->uploadAttachments($file, $post->id);
                }

            DB::commit();

        }catch (Exception $ex)
        {
            DB::rollBack();

            throw $ex;
        }

    }
}
