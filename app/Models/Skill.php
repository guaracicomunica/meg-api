<?php

namespace App\Models;

use App\Utils\File;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Utils\Arr;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Skill extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'path',
        'coins',
        'classroom_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'pivot',
        'created_at',
        'updated_at',
        'classroom_id',
        'id'
    ];

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    public function getClaimSkillMessage(User $user): string
    {
        $now = Carbon::now();
        return "{$user->name} requisitou a habilidade {$this->name} às {$now}";
    }

    /**
     * Get the level's banner updated to the correct URL disponibilization.
     *
     * @param  string  $value
     * @return void
     */
    public function getPathAttribute($value)
    {
        return is_null($value) ? $value : File::formatLink($value);
    }
}
