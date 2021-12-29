<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Handlers\EnrollClassroomHandler;
use App\Http\Handlers\GetAllClassroomHandler;
use App\Http\Handlers\GetParticipantsClassroomHandler;
use App\Http\Handlers\GetPostsClassroomHandler;
use App\Http\Handlers\ManageClassroomHandler;
use App\Http\Requests\CreateActivityRequest;
use App\Http\Requests\CreateNewsRequest;
use App\Http\Requests\EnrollClassroomRequest;
use App\Http\Requests\ManageClassroomRequest;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class ClassroomController extends Controller
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

    /****
     * Get all classrooms
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request) : JsonResponse
    {
        $classes = GetAllClassroomHandler::handle($request);
        return response()->json($classes);
    }

    /****
     * Manage classroom - create and update as draft or not
     * @param ManageClassroomRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(ManageClassroomRequest $request) : JsonResponse
    {
        ManageClassroomHandler::handle($request->all());
        return response()->json([
            'message' => 'Classroom successfully managed'
        ], 201);
    }

    /***
     * Make an enrollment - user get in a classroom
     * @param EnrollClassroomRequest $request
     * @return JsonResponse
     */
    public function enrollment(EnrollClassroomRequest $request) : JsonResponse
    {
        EnrollClassroomHandler::handle($request->all());
        return response()->json([
            'message' => 'Enrollment successfully done',
        ], 201);
    }

    /****
     * Get all participants of a classroom
     * @param int $id
     * @return JsonResponse
     */
    public function participants(int $id) : JsonResponse
    {
        $result = GetParticipantsClassroomHandler::handle($id);
        return response()->json($result);
    }

    /***
     * Get all posts of a classroom
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     */
    public function posts(int $id, Request $request) : JsonResponse
    {
        $result = GetPostsClassroomHandler::handle($id, $request);
        return response()->json($result);
    }

    /***
     * @param CreateActivityRequest $request
     * @return JsonResponse
     */
    public function storeActivity(CreateActivityRequest $request) : JsonResponse
    {
        Post::create($request->all());

        return response()->json([
            'message' => 'Activity successfully registered',
        ],201);
    }

    /***
     * @param CreateNewsRequest $request
     * @return JsonResponse
     */
    public function storeNews(CreateNewsRequest $request) : JsonResponse
    {
        Post::create($request->all());

        return response()->json([
            'message' => 'News successfully registered',
        ], 201);
    }
}
