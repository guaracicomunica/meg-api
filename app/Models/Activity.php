<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Contracts\Auditable;

class Activity extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $table = 'activities';

    protected $fillable = [
        'points',
        'xp',
        'coins',
        'deadline',
        'post_id',
        'topic_id',
        'unit_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    /***
     * palavras reservadas: proibidas para atualização em atividade caso ao menos um aluno tenha sido pontuado.
     */
    const reservedWords = ["xp", "coins", "points", "unit_id", 'disabled'];

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function topic(): BelongsTo
    {
        return $this->belongsTo(Topic::class, 'topic_id');
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'users_activities', 'activity_id', 'user_id');
    }

    public function calcXpFromStudentGrade($studentPoints)
    {
        return ($studentPoints * $this->xp) / $this->points;
    }

    public function calcCoinsFromStudentGrade($studentPoints)
    {
        return ($studentPoints * $this->coins) / $this->points;
    }

    public function userActivity()
    {
        return $this->hasMany(UserActivity::class, 'activity_id');
    }
    /**
     * @param int $classroomId
     * @return void
     */
    public function assignToAllStudents(int $classroomId)
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

    /***
     * @param $studentId
     * @return void
     */
    public function assignToStudent($studentId)
    {
        $data = [
            'points' => 0,
            'coins' => 0,
            'xp' => 0,
            'delivered_at' => null,
            'scored_at' => null,
            'user_id' => $studentId,
            'activity_id' => $this->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];

        $match = ['user_id' => $studentId, 'activity_id' => $this->id];

        UserActivity::updateOrCreate($match,$data);
    }

    /**
     * @return bool
     */
    public function atLeastOneStudentScored(): bool
    {
        $count = UserActivity::where('activity_id', $this->id)
            ->whereNotNull('scored_at')
            ->count();

        return $count > 0;
    }

}
