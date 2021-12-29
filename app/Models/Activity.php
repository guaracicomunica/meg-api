<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $table = 'activities';

    protected $fillable = [
        'points',
        'xp',
        'coins',
        'deadline',
        'post_id',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }
}
