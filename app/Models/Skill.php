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
        'coins',
        'classroom_id'
    ];
}
