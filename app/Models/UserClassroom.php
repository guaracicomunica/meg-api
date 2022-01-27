<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserClassroom extends Model
{
    use HasFactory;

    protected $table = 'users_classrooms';

    protected $fillable = [
        'id',
        'user_id ',
        'xp',
        'level_id',
        'classroom_id',
        'created_at',
        'updated_at',
    ];

}
