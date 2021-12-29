<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static findOrFail(int $id)
 */
class Post extends Model
{
    use HasFactory;

    protected $table = 'posts';

    protected $fillable = [
        'name',
        'body',
        'deadline',
        'points',
        'coins',
        'xp',
        'disabled',
        'classroom_id',
        'post_type_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function classroom()
    {
        return $this->belongsTo(Post::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
