<?php

namespace App\Models;

use App\Utils\UniqueCode;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    /**
     * @throws \Throwable
     */
    public static function createClassroom(array $data) : Classroom
    {
        $assignedValues = [
            'id' => $data['id'],
            'name'=> $data['name'],
            'description'=> $data['description'],
            'code' => UniqueCode::generate(),
            'status' => !$data['is_draft'],
            'banner' => null,
            'creator_id' => (int) $data['creator_id']
        ];

        try {
            DB::beginTransaction();

            $classroom = Classroom::where('id', $data['id'])->first();

            if($classroom == null)
            {
                $classroom = self::create($assignedValues);
            } else {
                $classroom->updateWithoutRefreshCode($assignedValues);
            }

            if($data['levels'])
            {
                Level::createAndAssignToClassroom($data['levels'], $classroom->id);
            }

            if($data['skills'])
            {
                Skill::createAndAssignToClassroom($data['skills'], $classroom->id);
            }

            DB::commit();

            return $classroom;
        } catch (\Throwable $ex)
        {
            DB::rollback();
            throw $ex;
        }
    }

    public function updateWithoutRefreshCode(array $items): bool
    {
        unset($items['code']);
        return $this->update($items);
    }
}
