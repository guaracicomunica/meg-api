<?php

namespace App\Http\Resources;

use App\Utils\StringUtil;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use function PHPUnit\Framework\isNull;

class ClassroomResource extends JsonResource
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
            'nickname' => $this->nickname,
            'banner' => $this->banner,
            'code' => $this->code,
            'roleUser' => Auth::user()->role_id,
            'teacher' => $this->creator->name,
        ];
    }
}
