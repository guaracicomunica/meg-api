<?php

namespace App\Http\Handlers;

use App\Http\Requests\GetOneReportCardRequest;
use App\Utils\RowQuery;

class GetOneReportCardHandler
{
    public static function handle(GetOneReportCardRequest $request)
    {
        return RowQuery::getReportCardOfStudent(
            $request->get('classroom_id'),
            $request->get('user_id')
        );
    }
}
