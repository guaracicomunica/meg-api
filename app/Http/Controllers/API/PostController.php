<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Handlers\GetAllPostsHandler;
use App\Http\Requests\CreateActivityRequest;
use App\Http\Requests\CreateNewRequest;
use App\Http\Requests\CreateNewsRequest;
use App\Http\Requests\GetAllPostsRequest;
use App\Http\Requests\PostRequest;
use App\Models\Post;
use Illuminate\Http\JsonResponse;

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

    public function index(GetAllPostsRequest $request)
    {
        $result = GetAllPostsHandler::handle($request);
        return response()->json($result);
    }

    /****
     * Create activity
     * @param CreateActivityRequest $request
     * @return JsonResponse
     */
    public function storeActivity(CreateActivityRequest $request) : JsonResponse
    {
        CreatePostHandler::handle($request->all());
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
        CreatePostHandle::handle($request->all());
        return response()->json([
            'message' => 'Post successfully registered',
        ], 201);
    }
}
