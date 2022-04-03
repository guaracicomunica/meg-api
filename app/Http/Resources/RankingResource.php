<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RankingResource extends JsonResource
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
            'name' => $this->user->name,
            'xp' => $this->xp,
            'avatar' => $this->user->avatar_path,
            'level' => $this->level != null ? [
                'name' => $this->level->name,
                'avatar' => $this->level->path,
            ] : null,
        ];
    }
}
