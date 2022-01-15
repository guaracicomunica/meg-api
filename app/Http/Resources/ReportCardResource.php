<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ReportCardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'user' => $this->name,
            'bim1' => $this->getAverage($this->reportCards, 1),
            'bim2' => $this->getAverage($this->reportCards, 2),
            'bim3' => $this->getAverage($this->reportCards, 3),
            'bim4' => $this->getAverage($this->reportCards, 4),
        ];
    }

    public function getAverage($reportCards, $unit) : float
    {
        if($reportCards == null) return 0;

        $filteredReports = $reportCards->filter(function($record) use ($unit) {
            return $record->unit_id == $unit;
        });

        return count($filteredReports) > 0 ? $filteredReports->first()->average : 0;
    }
}
