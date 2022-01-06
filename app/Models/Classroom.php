<?php

namespace App\Models;

use App\Utils\File;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static create(array $assignedValues)
 * @method static where(string $string, mixed $id)
 * @method static findOrFail(int $id)
 */
class Classroom extends Model
{
    use HasFactory;

    protected $hidden = ['pivot', 'created_at', 'updated_at'];

    protected $fillable = [
        'name',
        'nickname',
        'status',
        'code',
        'banner',
        'creator_id'
    ];

    public function levels(): HasMany
    {
        return $this->hasMany(Level::class, 'classroom_id');
    }

    public function skills(): HasMany
    {
        return $this->hasMany(Skill::class, 'classroom_id');
    }

    public function participants()
    {
        return $this->belongsToMany(User::class, 'users_classrooms', 'classroom_id', 'user_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'classroom_id');
    }

    public function teachers()
    {
        return $this->belongsToMany(User::class, 'users_classrooms', 'classroom_id', 'user_id');
    }

    public function activities(): HasMany
    {
        return $this->hasMany(Post::class, 'classroom_id');
    }

    public function uploadBanner($file)
    {
        $path = File::saveAs(
            "public/classrooms/{$this->id}",
            $file,
            "banner"
        );

        if($path != null)
        {
            $this->banner = $path;

            $this->save();
        }
    }

    /**
     * Update classroom, ignoring fields such as code (unique)
     * and creator_id, which must be immutable.
     * @returns boolean
     */
    public function updateSafely(array $items): bool
    {
        unset($items['code']);
        unset($items['creator_id']);
        return $this->fill($items)->save();
    }
}
