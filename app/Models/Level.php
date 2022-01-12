<?php

namespace App\Models;

use App\Utils\Arr;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static whereNotIn(string $string, array $names)
 */
class Level extends Model
{
    use HasFactory;

    protected $fillable = [
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
        'id'
    ];
}
