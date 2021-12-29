<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateActivityRequest;
use App\Http\Requests\CreateNewRequest;
use App\Http\Requests\CreateNewsRequest;
use App\Http\Requests\PostRequest;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
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
        $result = Post::where('classroom_id', $request->classroom_id)->paginate($request->per_page);
        return response()->json($result);
    }

    /****
     * Create activity
     * @param CreateActivityRequest $request
     * @return JsonResponse
     */
    public function storeActivity(CreateActivityRequest $request) : JsonResponse
    {
        Post::create($request->all());
        return response()->json([
            'message' => 'Post successfully registered',
        ], 201);
    }

    /****
     * @param CreateNewRequest $request
     * @return JsonResponse
     */
    public function storeNews(CreateNewsRequest $request) : JsonResponse
    {
        Post::create($request->all());
        return response()->json([
            'message' => 'Post successfully registered',
        ], 201);
    }
}
