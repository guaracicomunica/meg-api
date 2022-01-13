<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public static function findByKeys(int $studentId, int $activityId)
    {
        return self::where('user_id', $studentId)
            ->where('activity_id', $activityId)
            ->firstOrFail();
    }

    public function updateActivitySituation(float $grade, int $xp, int $coins)
    {
        return $this->update([
            'points' => $grade,
            'xp' => $xp,
            'coins' => $coins,
            'scored_at' => Carbon::now(),
        ]);
    }
}
