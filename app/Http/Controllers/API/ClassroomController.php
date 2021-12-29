<?php

namespace App\Http\Controllers\API;

use App\Handlers\CreateClassroomHandler;
use App\Handlers\EnrollClassroomHandler;
use App\Handlers\GetAllClassroomHandler;
use App\Handlers\GetParticipantsClassroomHandler;
use App\Handlers\GetPostsClassroomHandler;
use App\Http\Requests\CreateClassroomRequest;
use App\Http\Requests\EnrollClassroomRequest;
use App\Http\Controllers\Controller;
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
     * @param CreateClassroomRequest $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(CreateClassroomRequest $request) : JsonResponse
    {
        CreateClassroomHandler::handle($request->all());
        return response()->json([
            'message' => 'Classroom successfully registered'
        ]);
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
        ]);
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
}
