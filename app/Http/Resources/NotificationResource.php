<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
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
            'id' => $this->id,
            'skill' => $this->skill->name,
            'classroom' => $this->skill->classroom->name,
            'claimer' => [
                'name' => $this->claimer->name,
                'avatar' => $this->claimer->avatar_path,
            ],
            'created_at' => $this->created_at,
        ];
    }
}
