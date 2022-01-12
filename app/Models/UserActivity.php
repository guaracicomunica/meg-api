<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static firstOrCreate(array $data)
 */
class UserActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'points',
        'coins',
        'xp',
        'delivered_at',
        'scored_at',
        'user_id',
        'activity_id',
    ];

    protected $table = 'users_activities';
}
