<?php

namespace App\Http\Resources;

use App\Models\UserActivity;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityTeacherResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $activity = [
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
            'totalDeliveredActivities' => UserActivity::where('activity_id', $this->id)->whereNotNull('delivered_at')->count(),
            'totalAssignments' => UserActivity::where('activity_id', $this->id)->count(),
        ];

        return $activity;
    }
}

