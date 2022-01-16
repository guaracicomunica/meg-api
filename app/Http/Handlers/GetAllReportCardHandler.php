<?php

namespace App\Http\Handlers;

use App\Http\Requests\GetAllReportCardRequest;
use App\Http\Resources\ReportCardTeacherResource;
use App\Models\User;
use App\Utils\RowQuery;

class GetAllReportCardHandler
{
    public static function handle(GetAllReportCardRequest $request)
    {
        $users = User::whereHas('reportCards',
            function ($query) use ($request){
                $query->where('classroom_id', $request->get('classroom_id'));
        })->paginate($request->get('per_page'));

        return ReportCardTeacherResource::collection($users)->response()->getData();
    }
}
