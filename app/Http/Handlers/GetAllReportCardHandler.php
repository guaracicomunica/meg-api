<?php

namespace App\Http\Handlers;

use App\Http\Requests\GetAllReportCardRequest;
use App\Http\Resources\ReportCardResource;
use App\Utils\RowQuery;

class GetAllReportCardHandler
{
    public static function handle(GetAllReportCardRequest $request)
    {
        return RowQuery::getReportCard($request->get('classroom_id'));
    }
}
