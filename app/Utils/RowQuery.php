<?php

namespace App\Utils;

use Illuminate\Support\Facades\DB;

class RowQuery
{
    /***
     * Display report card of classroom students.
     * @param $classroomId
     * @return array
     */
    public static function getReportCard($classroomId): array
    {
        return DB::select(self::getQuery($classroomId, null));
    }


    /***
     * Display report card of classroom one student.
     * @param $classroomId
     * @param $studentId
     * @return array
     */
    public static function getReportCardOfStudent($classroomId, $studentId)
    {
        return DB::select(self::getQuery($classroomId, $studentId))[0];
    }

    /***
     * Utiliza raw sql para exibir boletim.
     * Foi aplicada técnica de pivotação, isto é,
     * converter as linhas da tabela em colunas.
     * @param $classroomId
     * @param $studentId
     * @return string
     */
    public static function getQuery($classroomId, $studentId)
    {
        $subQuery = "SELECT report_cards.*, CASE WHEN unit_id = 1 THEN average END AS A, CASE WHEN unit_id = 2 THEN average END AS B, CASE WHEN unit_id = 3 THEN average END AS C, CASE WHEN unit_id = 4 THEN average END AS D FROM report_cards
        where classroom_id = {$classroomId}";

        if($studentId != null)
        {
            $subQuery .= " and user_id = {$studentId}";
        }

        return "SELECT rc.user_id,
                SUM(A) AS bim1,
                SUM(B) AS bim2,
                SUM(C) AS bim3,
                SUM(D) AS bim4
            FROM ( {$subQuery} ) AS rc
            GROUP BY rc.user_id";
    }
}
