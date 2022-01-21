<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Handlers\CreateActivityHandler;
use App\Http\Handlers\DeliveryActivityHandler;
use App\Http\Handlers\GetAllActivityHandler;
use App\Http\Handlers\GetAllSolversActivityHandler;
use App\Http\Handlers\GetOneActivityHandler;
use App\Http\Handlers\GradeStudentsActivityHandler;
use App\Http\Handlers\UpdateActivityHandler;
use App\Http\Requests\CreateActivityRequest;
use App\Http\Requests\DeliveryActivityRequest;
use App\Http\Requests\GetAllSolversActivityRequest;
use App\Http\Requests\GradeStudentsActivityRequest;
use App\Http\Requests\UpdateActivityRequest;
use App\Models\UserActivity;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityController extends Controller
{
    /**
     * Create a new ActivityController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(Request $request) : JsonResponse
    {
        $result = GetAllActivityHandler::handle($request);
        return response()->json($result);
    }

    public function show(int $id): JsonResponse
    {
        $result = GetOneActivityHandler::handle($id);
        return response()->json($result);
    }

    /***
     * @param int $id
     * @param UpdateActivityRequest $request
     * @return JsonResponse
     */
    public function update(int $id, UpdateActivityRequest $request): JsonResponse
    {
        UpdateActivityHandler::handle($id, $request);
        return response()->json([
            'message' => 'activity successfully updated'
        ]);
    }

    public function store(CreateActivityRequest $request) : JsonResponse
    {
        CreateActivityHandler::handle($request->all());
        return response()->json([
            'message' => 'activity successfully registered'
        ]);
    }

    /***
     * @param GetAllSolversActivityRequest $request
     * @return JsonResponse
     */
    public function getSolvers(GetAllSolversActivityRequest $request) : JsonResponse
    {
        $result = GetAllSolversActivityHandler::handle($request);
        return response()->json($result);
    }

    /***
     * @param DeliveryActivityRequest $request
     * @return JsonResponse
     */
    public function delivery(DeliveryActivityRequest $request): JsonResponse
    {
        DeliveryActivityHandler::handle($request->all());
        return response()->json([
            'message' => 'activity successfully delivered'
        ]);
    }

    /***
     * Cancelar uma entrega
     * @param DeliveryActivityRequest $request
     * @return JsonResponse
     */
    public function cancel(DeliveryActivityRequest $request): JsonResponse
    {
        $userActivity = UserActivity::where('activity_id', $request->activity_id)->where('user_id', Auth::user()->id)->first();

        if($userActivity->scored_at != null)
        {
            return response()->json([
                'errors' => 'A entrega não pode ser cancelada, pois você já teve uma nota atribuída'
            ], 404);
        }

        $userActivity->delivered_at = null;

        $userActivity->save();

        return response()->json([
            'message' => 'activity successfully undelivered'
        ]);
    }


    /**
     * @param GradeStudentsActivityRequest $request
     * @return JsonResponse
     */
    public function grade(GradeStudentsActivityRequest $request): JsonResponse
    {
        GradeStudentsActivityHandler::handle($request);
        return response()->json([
            'message' => 'students who solved activity are successfully graded'
        ]);
    }



}
