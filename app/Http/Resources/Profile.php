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
        $resource = parent::toArray($request);

        $explode_name = explode(" ", $resource['name']);

        $resource['first_name'] = $explode_name[0];
        $resource['last_name'] =  (count($explode_name) > 1) ? end($explode_name) : '';

        return [
            'id' => $this->id,
            'name' => $this->name,
            'nickname' => $this->nickname,
            'privilege_id' => $this->privilege_id,
            'photo' => $this->photo,
            'photo_url' => UserPhoto::get($this->photo),
            'first_name' => $resource['first_name'],
            'last_name' => $resource['last_name'],
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
