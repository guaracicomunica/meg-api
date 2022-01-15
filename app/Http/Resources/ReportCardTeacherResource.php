<?php

namespace App\Http\Resources;

use App\Models\ReportCard;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportCardTeacherResource extends JsonResource
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
            'bim1' => ReportCard::getAverageOfUnit($this->reportCards, 1),
            'bim2' => ReportCard::getAverageOfUnit($this->reportCards, 2),
            'bim3' => ReportCard::getAverageOfUnit($this->reportCards, 3),
            'bim4' => ReportCard::getAverageOfUnit($this->reportCards, 4),
        ];
    }
}
