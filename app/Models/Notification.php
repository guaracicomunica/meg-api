<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'author_id',
        'recipient_id',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
