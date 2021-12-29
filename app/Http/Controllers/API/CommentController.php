<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\CreateCommentRequest;
use App\Models\Comment;
use App\Http\Controllers\Controller;
use App\Models\Post;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use function PHPUnit\Framework\isNull;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /***
    *
     * @param int $id
     * id da classe
     */
    public function index(int $id)
    {
        try{

            $comments = Comment::where('post_id', $id)->latest()->paginate(10);

            return response( [
                $comments
            ], 200);

        } catch(Throwable $ex)
        { return response($ex->getMessage(), $ex->getCode()); }
    }

    public function store(CreateCommentRequest $request)
    {
        try {

            Post::findOrFail($request->post_id);

            $comment = Comment::create($request->all());

            return response([
                'message' => 'Comment successfully registered',
                'comment' => $comment
            ], 200);

        } catch(Throwable $ex)
        {
            return response($ex->getMessage(), $ex->getCode());
        }
    }



    public function delete(int $id)
    {
        try {

            Comment::findOrFail($id)->delete();

            return response([
                'message' => 'Comment was deleted',
            ], 200);

        } catch(Throwable $ex)
        {
            return response($ex->getMessage(), $ex->getCode());
        }
    }

}
