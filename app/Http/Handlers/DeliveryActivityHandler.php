<?php

namespace App\Http\Handlers;

use App\Models\PostFile;
use App\Models\UserActivityDeliveryFile;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DeliveryActivityHandler
{
    public static function handle(array $data)
    {
        //marca atividade como entregue
        DB::table('users_activities')
            ->where('activity_id', $data['activity_id'])
            ->where('user_id', Auth::user()->id)
            ->update(['delivered_at' => Carbon::now()]);

        //delivery's activity files upload
        if(isset($data['files']))
        {
            foreach ($data['files'] as $file)
            {
                $fileDelivery = new UserActivityDeliveryFile();
                $fileDelivery->uploadActivityAttachment($file, $data['activity_id'], Auth::user()->id);
            }
        }

    }
}
