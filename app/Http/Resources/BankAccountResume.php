<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BankAccountResume extends JsonResource
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
            "id" => $this->id,
            "uuid" => $this->uuid,
            "user" => $this->userToArray($this->user),
            "account_number" => $this->account_number,
            "account_holder" => $this->account_holder,
        ];
    }

    private function userToArray($user)
    {
        return array_merge($user->toArray(), [ 'member_class' => $user->member_class ]);
    }
}