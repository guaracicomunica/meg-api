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
}
