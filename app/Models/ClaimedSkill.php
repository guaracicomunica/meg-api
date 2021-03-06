<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClaimedSkill extends Model
{
    use HasFactory;

    protected $table = 'claimed_skills';

    protected $fillable = [
        'user_id',
        'skill_id',
        'created_at',
        'updated_at',
    ];
}
