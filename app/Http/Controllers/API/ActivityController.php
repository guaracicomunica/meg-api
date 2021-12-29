<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\DeliveryActivityRequest;
use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function show(int $id)
    {
        $result = Activity::findOrFail($id);
        return response()->json($result);
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
