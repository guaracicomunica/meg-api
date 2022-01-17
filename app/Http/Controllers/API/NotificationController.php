<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(Request $request)
    {
        $notifications = Notification::with('creator')
            ->where('recipient_id', Auth::user()->id)
            ->paginate($request->get('per_page'));

        $result = NotificationResource::collection($notifications)->response()->getData();

        return response()->json($result);
    }
}
