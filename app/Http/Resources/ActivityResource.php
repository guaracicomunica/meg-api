<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ActivityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
       // dd($this);
        //dd($this->post->comments);
        $activity = [
            'id' => $this->id,
            'name' => $this->post->name,
            'body' => $this->post->body,
            'coins' => floatval($this->coins),
            'xp' => floatval($this->xp),
            'deadline' => $this->deadline,
            'points' => floatval($this->points),
            'topicId' => $this->topic_id,
            'comments' => CommentResource::collection($this->post->comments),
            'attachments' => AttachmentsResource::collection($this->post->attachments)
        ];

        return $activity;
    }
}

