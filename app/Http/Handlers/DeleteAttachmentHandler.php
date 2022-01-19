<?php

namespace App\Http\Handlers;

use App\Models\PostAttachment;
use App\Utils\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DeleteAttachmentHandler
{

    /***
     * @param $id
     * @return void
     */
    public static function handle($id)
    {
        try {
            DB::beginTransaction();

            $attachment = PostAttachment::whereHas('post', function($query){
                $query->where('posts.creator_id', Auth::user()->id);
            })->findOrFail($id);

            File::delete($attachment->path);

            DB::commit();
        } catch (Exception $ex)
        {
            DB::rollBack();
            throw $ex;
        }
    }
}
