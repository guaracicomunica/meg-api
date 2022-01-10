<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserStatusGamefication extends Model
{
    use HasFactory;
    protected $table = 'user_status_gamefications';
    protected $fillable = [
        'coins',
        'xp',
        'user_id',
        'created_at',
        'updated_at',
    ];
}
