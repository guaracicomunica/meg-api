<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static create(array $assignedValues)
 * @method static where(string $string, mixed $id)
 */
class Classroom extends Model
{
    use HasFactory;

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
