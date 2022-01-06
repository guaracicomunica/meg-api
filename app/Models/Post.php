<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
        'classroom_id'
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

    public function activity()
    {
        return $this->hasOne(Activity::class);
    }
}
