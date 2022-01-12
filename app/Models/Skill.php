<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Utils\Arr;

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
}
