<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\CreateClassroomRequest;
use App\Models\Classroom;
use App\Http\Controllers\Controller;
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

    public function index()
    {
        dd("example");
    }

    public function store(CreateClassroomRequest $request)
    {
        try {
            $classroom = Classroom::createClassroom($request->all());
            return response([
                'message' => 'Classroom successfully registered',
                'classroom' => $classroom
            ], 200);
        } catch(Throwable $ex)
        {
            return response($ex->getMessage(), 500);
        }
    }
}
