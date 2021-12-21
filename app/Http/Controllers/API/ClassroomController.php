<?php

namespace App\Http\Controllers\API;

use App\Models\Classroom;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}
