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
            'averages' => $this->reportCards->map(function($item) {
                return [
                    'unit' => $item->id,
                    'average' => $item->average
                ];
            }),
        ];
    }
}
