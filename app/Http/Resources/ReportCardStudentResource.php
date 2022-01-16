<?php

namespace App\Http\Resources;

use App\Models\ReportCard;
use Illuminate\Http\Resources\Json\JsonResource;

class ReportCardStudentResource extends JsonResource
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
            'user' => $this->user->name,
            'classroom' => $this->classroom->name,
            'level' => $this->level->name ?? null,
            'xp' => $this->xp,
            'coins' => $this->user->gamefication->coins,
            'bim1' => ReportCard::getAverageOfUnit($this->user->reportCards, 1),
            'bim2' => ReportCard::getAverageOfUnit($this->user->reportCards, 2),
            'bim3' => ReportCard::getAverageOfUnit($this->user->reportCards, 3),
            'bim4' => ReportCard::getAverageOfUnit($this->user->reportCards, 4),
        ];
    }
}
