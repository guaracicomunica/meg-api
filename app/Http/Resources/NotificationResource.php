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
            'content' => $this->content,
            'creator' => [
                'name' => $this->creator->name,
                'avatar' => $this->creator->avatar_path,
            ],
            'created_at' => $this->created_at,
        ];
    }
}
