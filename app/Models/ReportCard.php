<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportCard extends Model
{
    use HasFactory;

    protected $table = 'report_cards';

    protected $fillable = [
        'average',
        'user_id',
        'classroom_id',
        'unit_id',
        'created_at',
        'updated_at',
    ];
}
