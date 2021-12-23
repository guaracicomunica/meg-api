<?php

namespace App\Models;

use App\Utils\UniqueCode;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'status',
        'code',
        'banner',
        'creator_id'
    ];

    public function levels(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Level::class, 'classroom_id');
    }

    public function skills(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Skill::class, 'classroom_id');
    }

    public static function createClassroom($request)
    {
        $match = ['name' => $request->name, 'creator_id' => $request->creator_id];

        $classroom = self::updateOrCreate($match, [
            'name'=> $request->name,
            'description'=>$request->description,
            'code' => UniqueCode::generate(),
            'status' => !$request->is_draft,
            'banner' => null,
            'creator_id' => $request->creator_id
        ]);

        if($request->levels)
        {
            Level::createAndAssignToClassroom($request->levels, $classroom->id);
        }

        if($request->skills)
        {
            Skill::createAndAssignToClassroom($request->skills, $classroom->id);
        }

        return $classroom;
    }
}
