<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\CreateCommentRequest;
use App\Models\Comment;
use App\Http\Controllers\Controller;
use App\Models\Post;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /***
    *
     * @param int $postId
     * id do post associado ao comentário
     */
    public function index(int $postId)
    {
        $result = Comment::where('post_id', $postId)->latest()->paginate();
        return response()->json($result);
    }

    public function store(CreateCommentRequest $request)
    {
        Post::findOrFail($request->post_id);

        Comment::create($request->all());

        return response()->json([
            'message' => 'Comment successfully registered',
        ], 201);
    }

    public function delete(int $id)
    {
        Comment::findOrFail($id)->delete();

        return response()->json([
            'message' => 'Comment was deleted',
        ], 200);
    }
}
