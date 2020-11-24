<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use Storage;

class UserAddress extends JsonResource
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
            'address_type' => $this->address_type,
            'zip_code' => $this->zip_code,
            'state' => $this->state,
            'city' => $this->city,
            'neighborhood' => $this->neighborhood,
            'street_address' => $this->street_address,
            'number' => $this->number,
            'complement' => $this->complement,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}