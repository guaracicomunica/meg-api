<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;

class ReportCard extends Model implements Auditable
{
    use HasFactory, \OwenIt\Auditing\Auditable;

    protected $table = 'report_cards';

    protected $fillable = [
        'average',
        'user_id',
        'classroom_id',
        'unit_id',
        'created_at',
        'updated_at',
    ];

    /***
     * @param $reportCards
     * @param $unit
     * @return null
     */
    public static function getAverageOfUnit($reportCards, $unit)
    {
        if($reportCards == null) return null;

        $filteredReports = $reportCards->filter(function($record) use ($unit) {
            return $record->unit_id == $unit;
        });

        return count($filteredReports) > 0 ? $filteredReports->first()->average : null;
    }

    /***
     * Create report with default values such as: average = zero
     * @param $userId
     * @param $classroomId
     * @return void
     */
    public static function createDefaultForStudent($userId, $classroomId)
    {
        for($i = 0; $i <= 3; $i++)
        {
            ReportCard::firstOrCreate([
                'unit_id' => $i + 1,
                'user_id' => $userId,
                'classroom_id' => $classroomId,
            ]);
        }
    }

    /***
     * @param $userId
     * @param $classroomId
     * @return void
     */
    public static function exclude($userId, $classroomId)
    {
        ReportCard::where('user_id', $userId)->where('classroom_id', $classroomId)->delete();
    }
}
