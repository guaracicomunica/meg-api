<?php

namespace App\Http\Controllers\API;

use App\Models\Classroom;
use App\Http\Controllers\Controller;
use App\Utils\UniqueCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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

    public function store(Request $request)
    {
        try {
            Classroom::createClassroom($request);
        } catch(\Throwable $ex)
        {
            DB::rollBack();
            return response($ex->getMessage(), 500);
        }
    }
}
