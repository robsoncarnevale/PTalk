<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Profile extends JsonResource
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
            'privilege_id' => $this->privilege_id,
            'photo' => $this->photo,
            'photo_url' => UserPhoto::get($this->photo),
            'company' => $this->company,
            'company_activities' => $this->company_activities,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            // 'status' => $this->status,
            'type' => $this->type,
            'vehicles' => MemberVehicle::collection($this->vehicles)
        ];
    }
}
