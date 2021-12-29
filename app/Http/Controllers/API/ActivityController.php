<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Handlers\API\CreateActivityHandler;
use App\Http\Requests\CreateActivityRequest;
use App\Http\Requests\DeliveryActivityRequest;
use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index()
    {
        $result = Activity::with(['post'])->paginate();
        return response()->json($result);
    }

    public function show(int $id)
    {
        $result = Activity::findOrFail($id)->with(['post']);
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
