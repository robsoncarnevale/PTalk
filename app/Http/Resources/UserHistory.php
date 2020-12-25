<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

// use Storage;

class UserHistory extends JsonResource
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
            'approval_history' => $this->approval_history,
            'status_history' => $this->status_history,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}