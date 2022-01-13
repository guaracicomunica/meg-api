<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bimester extends Model
{
    use HasFactory;

    protected $table = 'bimesters';

    protected $fillable = [
        'name',
    ];
}
