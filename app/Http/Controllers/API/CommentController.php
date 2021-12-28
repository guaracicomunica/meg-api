<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\CreateCommentRequest;
use App\Http\Requests\CreateNewRequest;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class CommentController extends Controller
{

    public function store(CreateCommentRequest $request)
    {
        try {

            $classroom = Comment::create($request->all());

            return response([
                'message' => 'Comment successfully registered',
                'post' => $classroom
            ], 200);

        } catch(Throwable $ex)
        {
            return response($ex->getMessage(), $ex->getCode());
        }
    }

}
