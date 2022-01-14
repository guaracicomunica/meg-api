<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Handlers\FillReportCardHandler;
use App\Http\Handlers\GetAllReportCardHandler;
use App\Http\Handlers\GetOneReportCardHandler;
use App\Http\Requests\FillReportCardRequest;
use App\Http\Requests\GetAllReportCardRequest;
use App\Http\Requests\GetOneReportCardRequest;
use Illuminate\Http\JsonResponse;

class ReportCardController extends Controller
{
    /***
     * @param GetAllReportCardRequest $request
     * @return JsonResponse
     */
    public function index(GetAllReportCardRequest $request): JsonResponse
    {
        $result = GetAllReportCardHandler::handle($request);
        return response()->json($result);
    }

    /**
     * @param GetOneReportCardRequest $request
     * @return JsonResponse
     */
    public function show(GetOneReportCardRequest $request): JsonResponse
    {
        $result = GetOneReportCardHandler::handle($request);
        return response()->json($result);
    }

    /**
     * @param FillReportCardRequest $request
     * @return JsonResponse
     */
    public function store(FillReportCardRequest $request): JsonResponse
    {
        FillReportCardHandler::handle($request);
        return response()->json([
            'message' => 'Report Cards successfully filled',
        ]);
    }
}
