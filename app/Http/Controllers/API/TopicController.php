<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Handlers\CreateTopicHandler;
use App\Http\Handlers\GetAllTopicsClassroomHandler;
use App\Http\Requests\GetAllTopicsRequest;
use Illuminate\Http\JsonResponse;

class TopicController extends Controller
{
    /**
     * Create a new TopicController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }


    /**
     * @param GetAllTopicsRequest $request
     * @return JsonResponse
     */
    public function index(GetAllTopicsRequest $request): JsonResponse
    {
        $result = GetAllTopicsClassroomHandler::handle($request);
        return response()->json($result);
    }

    /**
     * @param CreateTopicRequest $request
     * @return JsonResponse
     */
    public function store(CreateTopicRequest $request): JsonResponse
    {
        CreateTopicHandler::handle($request->all());
        return response()->json([
            'message' => 'Topic successfully registered',
        ], 201);
    }
}
