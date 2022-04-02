<?php

namespace App\Models;

use App\Utils\File;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use OwenIt\Auditing\Contracts\Auditable;

class UserActivityDeliveryFile extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $table = 'users_activities_delivered_files';

    protected $fillable = ['path', 'user_activity_id'];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    public function uploadActivityAttachment($file, int $activity_id, int $user_id)
    {
        $folder = "public/activity/{$activity_id}/$user_id";

        $hash_file = Str::random($user_id);

        $path = File::saveAs(
            $folder,
            $file,
            "delivered_{$hash_file}"
        );

        if($path != null)
        {
            $this->path = $path;
            $this->user_activity_id = $this->getUserActivityId($user_id, $activity_id);
            $this->save();
        }
    }

    public function getUserActivityId($user_id, $activity_id) {
        return UserActivity::
        where('activity_id', $activity_id)
            ->where('user_id', $user_id)
            ->firstOrFail()->id;
    }

    public function getPathAttribute($value)
    {
        return is_null($value) ? $value : File::formatLink($value);
    }
}
