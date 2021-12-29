<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Handlers\CreatePostHandler;
use App\Http\Handlers\GetAllPostsHandler;
use App\Http\Handlers\DeliveryActivityHandler;
use App\Http\Requests\CreateActivityRequest;
use App\Http\Requests\CreateNewRequest;
use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\GetAllPostsRequest;
use App\Http\Requests\PostRequest;
use App\Http\Requests\DeliveryActivityRequest;
use App\Models\Post;
use Illuminate\Http\Request;
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

    /***
     * Get all posts
     * @param GetAllPostsRequest $request
     * @return JsonResponse
     */
    public function index(GetAllPostsRequest $request): JsonResponse
    {
        $result = GetAllPostsHandler::handle($request);
        return response()->json($result);
    }

    /***
     * Show one post
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $result = Post::findOrFail($id);
        return response()->json($result);
    }

    /****
     * @param CreatePostRequest $request
     * @return JsonResponse
     */
    public function store(CreatePostRequest $request) : JsonResponse
    {
        CreatePostHandler::handle($request->all());
        return response()->json([
            'message' => 'Post successfully registered',
        ], 201);
    }
}
