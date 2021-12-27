<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\CreateClassroomRequest;
use App\Http\Requests\EnrollClassroomRequest;
use App\Models\Classroom;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
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
        try {
            $classes = Auth::user()->classes()->latest()->paginate(10);
            return response()->json($classes);
        } catch(Throwable $ex)
        {
            return response($ex->getMessage(), 500);
        }
    }

    public function store(CreateClassroomRequest $request)
    {
        try {
            Classroom::createClassroom($request->all());
            return response([
                'message' => 'Classroom successfully registered'
            ], 200);
        } catch(Throwable $ex)
        {
            return response($ex->getMessage(), 500);
        }
    }

    public function enrollment(EnrollClassroomRequest $request)
    {
        try {
            $data = $request->validated();
            return response()->json([
                'message' => 'Classroom successfully registered'
            ], 200);
        } catch(Throwable $ex)
        {
            return response($ex->getMessage(), 500);
        }
    }
}
