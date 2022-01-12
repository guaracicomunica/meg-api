<?php

namespace App\Http\Handlers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DeliveryActivityHandler
{
    public static function handle(array $data)
    {
        DB::table('users_activities')
            ->where('activity_id', $data['activity_id'])
            ->where('user_id', Auth::user()->id)
            ->update(['delivered_at' => Carbon::now()]);

        //upload de arquivos
    }
}
