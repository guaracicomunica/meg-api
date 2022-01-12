<?php

namespace App\Http\Handlers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DeliveryActivityHandler
{
    public static function handle(array $data)
    {
        DB::table('users_activities')
            ->where('activity_id', $data['activity_id'])
            ->update(['delivered_at' => Carbon::now()]);

        //upload de arquivos
    }
}
