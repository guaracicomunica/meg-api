<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'body' => $this->body,
            'date' => $this->created_at,
            'is_private' => $this->is_private,
            'creator' => [
                'name' => $this->creator->name,
                'avatar' => $this->creator->avatar_path,
            ],
            'comments' => CommentResource::collection($this->comments)
        ];
    }
}
