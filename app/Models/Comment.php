<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'body',
        'is_private',
        'user_id',
        'post_id'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

}
