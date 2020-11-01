<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BlacklistHistory extends JsonResource
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
            'status' => $this->status,
            'description' => $this->description,
            'changed_by' => [
                'id' => $this->getChangedBy->id,
                'name' => $this->getChangedBy->name,
                'nickname' => $this->getChangedBy->nickname,
                'display_name' => $this->getChangedBy->display_name,
                'photo' => UserPhoto::get($this->getChangedBy->photo),
            ],
            'created_at' => $this->created_at,
            'created_at_formatted' => $this->created_at_formatted,
        ];
    }
}