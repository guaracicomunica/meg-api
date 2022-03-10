<?php

namespace App\Models;

use App\Utils\Arr;
use App\Utils\File;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

/**
 * @method static whereNotIn(string $string, array $names)
 */
class Level extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $fillable =
    [
        'id',
        'name',
        'path',
        'xp',
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
    ];

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
