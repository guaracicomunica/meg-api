<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Handlers\CreateActivityHandler;
use App\Http\Handlers\GetAllActivityHandler;
use App\Http\Handlers\GetOneActivityHandler;
use App\Http\Requests\CreateActivityRequest;
use App\Http\Requests\DeliveryActivityRequest;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index()
    {
        $result = GetAllActivityHandler::handle();
        return response()->json($result);
    }

    public function show(int $id)
    {
        $result = GetOneActivityHandler::handle($id);
        return response()->json($result);
    }

    public function store(CreateActivityRequest $request)
    {
        CreateActivityHandler::handle($request->all());
        response()->json([
            'message' => 'activity successfully registered'
        ]);
    }

    public function delivery(DeliveryActivityRequest $request)
    {
        dd($request->all());
    }

    public function rate(Request $request)
    {
        dd($request->all());
    }
}
