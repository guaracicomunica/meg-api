<?php

namespace App\Http\Controllers\API;

use App\Handlers\CreateClassroomHandler;
use App\Handlers\EnrollClassroomHandler;
use App\Http\Requests\CreateClassroomRequest;
use App\Http\Requests\EnrollClassroomRequest;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Classroom;
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

    public function index(Request $request)
    {
        try {
            $classes = Auth::user()
                ->classes()
                ->with([
                    'levels',
                    'skills',
                    'participants' => function($query) {
                        $query->whereHas('roles', function(Builder $query){
                            $query->where('roles.id', 2);
                        })->select('email');
                    }
                ])
                ->latest()
                ->paginate($request->per_page);
            return response()->json($classes);
        } catch(Throwable $ex)
        {
            return response($ex->getMessage(), 500);
        }
    }

    public function store(CreateClassroomRequest $request)
    {
        try {
            CreateClassroomHandler::handle($request->all());
            return response([
                'message' => 'Classroom successfully registered'
            ]);
        } catch(Throwable $ex)
        {
            return response($ex->getMessage(), 500);
        }
    }

    public function enrollment(EnrollClassroomRequest $request)
    {
        try {
            EnrollClassroomHandler::handle($request->all());
            return response()->json([
                'message' => 'Enrollment successfully done',
            ]);
        } catch(Throwable $ex)
        {
            return response($ex->getMessage(), 500);
        }
    }

    public function participants(int $id)
    {
        try {
            $classroom = Classroom::find($id);

            if($classroom == null)
            {
                return response()->json([
                    'message' => 'Classroom not found',
                ], 404);
            } else {
                $result = $classroom->participants()->get();
                return response()->json($result);
            }
        } catch(Throwable $ex)
        {
            return response($ex->getMessage(), 500);
        }
    }
}
