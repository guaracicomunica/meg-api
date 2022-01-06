<?php

namespace App\Http\Handlers;

use Illuminate\Support\Facades\DB;

class DeliveryActivityHandler
{
    public static function handle(array $data)
    {
        DB::table('users_activities')->updateOrInsert();
        // users_activities ==> delivered_at == now;
        //
    }
}
