<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Handlers\CreateTopicHandler;
use App\Http\Requests\CreateTopicRequest;
use App\Models\Topic;

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

    public function index()
    {
        return response()->json(Topic::paginate()->all() , 200);
    }

    public function getOne(int $id)
    {
        return response()->json(Topic::findOrFail($id),200);
    }

    public function store(CreateTopicRequest $request)
    {
        CreateTopicHandler::handle($request->all());
        return response()->json([
            'message' => 'Topic successfully registered',
        ], 201);
    }
}
