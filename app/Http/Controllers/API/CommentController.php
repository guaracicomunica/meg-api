<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\CreateCommentRequest;
use App\Http\Resources\CommentResource;
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
        $comments = Comment::with('creator')
            ->where('post_id', $postId)
            ->where('is_private', false)
            ->latest()
            ->paginate();
        $result = CommentResource::collection($comments);
        /*
         * id
         * date - created_at
         * body
         * creator
         * */
        return response()->json($result);
    }

    /***
     *
     * @param int $postId
     * id do post associado ao comentário
     */
    public function getAllPrivate(int $postId)
    {
        $comments = Comment::with('creator')
            ->where('post_id', $postId)
            ->where('is_private', true)
            ->latest()
            ->paginate();
        $result = CommentResource::collection($comments);
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
