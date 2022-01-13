<?php

namespace App\Models;

use App\Utils\File;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class UserActivityDeliveryFile extends Model
{
    use HasFactory;

    protected $table = 'users_activity_delivery_files';

    protected $fillable = [ 'path' , 'activity_id'];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function uploadActivityAttachment($file, int $activity_id, int $user_id)
    {
        $hash_file = Str::random($user_id);
        $path = File::saveAs(
            "public/activity/{$activity_id}/$user_id/",
            $file,
            "deliveried_{$hash_file}"
        );

        if($path != null)
        {
            $this->path = $path;

            $this->activity_id  = $activity_id;

            $this->save();
        }
    }
}
