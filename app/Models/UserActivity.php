<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @method static firstOrCreate(array $data)
 */
class UserActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'points',
        'coins',
        'xp',
        'delivered_at',
        'scored_at',
        'user_id',
        'activity_id',
    ];

    protected $table = 'users_activities';

    public function activity()
    {
        return $this->belongsTo(Activity::class, 'activity_id');
    }

    public function deliveredFiles(): HasMany
    {
        return $this->hasMany(UserActivityDeliveryFile::class, 'user_activity_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function findByKeys(int $studentId, int $activityId)
    {
        return self::where('user_id', $studentId)
            ->where('activity_id', $activityId)
            ->firstOrFail();
    }

    public function updateActivitySituation(float $grade, int $xp, int $coins): bool
    {
        return $this->update([
            'points' => $grade,
            'xp' => $xp,
            'coins' => $coins,
            'scored_at' => Carbon::now(),
        ]);
    }

    public function alreadyScored(): bool
    {
        return $this->scored_at != null;
    }
}
