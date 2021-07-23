<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class EventMember extends JsonResource
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
            'vehicle' => $this->vehicle,
            'companions' => $this->companions,
            'amount' => $this->amount,
            'user' => $this->userArray($this->user),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    private function userArray($user)
    {
        return [
            'id' => $this->user->id,
            'name' => $this->user->name,
            'cpf' => $this->user->document_cpf,
            'phone' => $this->user->phone,
            'photo' => UserPhoto::get($this->user->photo),
            'email' => $this->user->email,
            'company' => $this->user->company,
            'status' => $this->user->status,
        ];
    }
}
