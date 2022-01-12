<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Activity extends Model
{
    use HasFactory;

    protected $table = 'activities';

    protected $fillable = [
        'points',
        'xp',
        'coins',
        'deadline',
        'post_id',
        'topic_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class, 'topic_id');
    }

    public function assignStudents(int $classroomId)
    {
        $students = Classroom::findOrFail($classroomId)
            ->students()->pluck('users.id');

        $data = [];

        foreach ($students as $student)
        {
            $data[] = [
                'points' => 0,
                'coins' => 0,
                'xp' => 0,
                'delivered_at' => null,
                'scored_at' => null,
                'user_id' => $student,
                'activity_id' => $this->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        if(count($data) > 0)
        {
            DB::table('users_activities')->insert($data);
        }
    }
}
