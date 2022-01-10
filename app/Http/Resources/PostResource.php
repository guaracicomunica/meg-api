<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
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
            'name' => $this->name,
            'body' => $this->body,
            'date' => $this->created_at,
            'comments' => CommentResource::collection($this->comments),
            'creator' => $this->creator->name,
            'activity' =>$this->activity,
        ];
    }
}
