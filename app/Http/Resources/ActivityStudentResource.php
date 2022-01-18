<?php

namespace App\Http\Resources;

use App\Models\UserActivityDeliveryFile;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityStudentResource extends JsonResource
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
            'name' => $this->post->name,
            'body' => $this->post->body,
            'coins' => floatval($this->coins),
            'xp' => floatval($this->xp),
            'deadline' => $this->deadline,
            'points' => floatval($this->points),
            'topicId' => $this->topic_id,
            'postId' => $this->post_id,
            'disabled' => $this->post->disabled,
            'comments' => CommentResource::collection($this->post->comments),
            'attachments' => AttachmentsResource::collection($this->post->attachments),
            'assignment' => [
                'xp' => $this->assignment->xp,
                'coins' => $this->assignment->coins,
                'points' => $this->assignment->points,
                'delivered_at' => $this->assignment->delivered_at,
                'scored_at' => $this->assignment->scored_at,
                'attachments' => UserActivityDeliveryFile::where('user_activity_id', $this->assignment->id)->get(['path']),
            ]
        ];
    }
}
