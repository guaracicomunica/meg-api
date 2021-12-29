<?php

namespace App\Http\Controllers\API;

use App\Handlers\CreateClassroomHandler;
use App\Handlers\EnrollClassroomHandler;
use App\Handlers\GetAllClassroomHandler;
use App\Handlers\GetParticipantsClassroomHandler;
use App\Http\Requests\CreateClassroomRequest;
use App\Http\Requests\EnrollClassroomRequest;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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

    public function index(Request $request)
    {
        $classes = GetAllClassroomHandler::handle($request);
        return response()->json($classes);
    }

    public function store(CreateClassroomRequest $request)
    {
        CreateClassroomHandler::handle($request->all());
        return response([
            'message' => 'Classroom successfully registered'
        ]);
    }

    public function enrollment(EnrollClassroomRequest $request)
    {
        EnrollClassroomHandler::handle($request->all());
        return response()->json([
            'message' => 'Enrollment successfully done',
        ]);
    }

    public function participants(int $id)
    {
        $result = GetParticipantsClassroomHandler::handle($id);
        return response()->json($result);
    }

    public function posts()
    {

    }
}
