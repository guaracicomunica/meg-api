<?php

namespace App\Models;

use App\Utils\File;
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
        'disabled',
        'classroom_id',
        'creator_id',
    ];

    protected $hidden = [
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

    public function attachments()
    {
        return $this->hasMany(PostFile::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

}
