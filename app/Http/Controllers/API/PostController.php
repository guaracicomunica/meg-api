<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateActivityRequest;
use App\Http\Requests\CreateNewRequest;
use App\Http\Requests\PostRequest;
use App\Models\Post;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Validator;
use \Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Create a new ClassroomController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'classroom_id' => 'required',
        ]);

        if($validator->fails()){
            return response()->json(['error' => $validator->errors()->toJson()], 401);
        }

        try {

            $posts = Post::where('classroom_id', $request->classroom_id)->get();

            return response()->json($posts);

        } catch(Throwable $ex)
        {
            return response($ex->getMessage(), $ex->getCode());
        }
    }


    public function storeActivity(CreateActivityRequest $request)
    {
        try {

            $classroom = Post::create($request->all());

            return response([
                'message' => 'Post successfully registered',
                'post' => $classroom
            ], 200);
        } catch(Throwable $ex)
        {
            return response($ex->getMessage(), $ex->getCode());
        }
    }

    public function storeNew(CreateNewRequest $request)
    {
        try {

            $classroom = Post::create($request->all());

            return response([
                'message' => 'Post successfully registered',
                'post' => $classroom
            ], 200);

        } catch(Throwable $ex)
        {
            return response($ex->getMessage(), $ex->getCode());
        }
    }

}
