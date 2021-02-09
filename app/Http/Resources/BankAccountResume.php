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
        return array_merge([
            "id" => $this->id,
            "uuid" => $this->uuid,
            "user" => $this->userToArray($this->user),
            "account_number" => $this->account_number,
            "account_holder" => $this->account_holder,
        ], $this->admin_data());
    }

    private function userToArray($user)
    {
        $user_data = $user->toArray();
        $user_data['photo_url'] = UserPhoto::get($user_data['photo']);

        return array_merge($user_data, [ 'member_class' => $user->member_class ]);
    }

    private function admin_data()
    {
        return [
            "balance" => $this->balance,
            "status" => $this->status,
        ];
    }
}