<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Handlers\CreateTopicHandler;
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

    public function store(CreateTopicRequest $request): JsonResponse
    {
        CreateTopicHandler::handle($request->all());
        return response()->json([
            'message' => 'Topic successfully registered',
        ], 201);
    }
}
