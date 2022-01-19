<?php

namespace App\Http\Handlers;

use App\Models\PostAttachment;
use Illuminate\Support\Facades\Auth;

class DeleteAttachmentHandler
{

    public static function handle($id)
    {
        PostAttachment::whereHas('post', function($query){
            $query->where('posts.creator_id', Auth::user()->id);
        })->findOrFail($id)->delete();
    }
}
