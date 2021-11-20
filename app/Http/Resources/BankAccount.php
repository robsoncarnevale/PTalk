<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BankAccount extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // $data = parent::toArray($request);

        $data = [
            'id' => $this->id,
            'account_number' => $this->account_number,
            'balance' => (float) $this->balance,
            'type' => [
                'id' => $this->type->id,
                'description' => __('bank_account.type.' . $this->type->description)
            ],
            'status' => [
                'id' => $this->status_id,
                'description' => __('status.' . $this->status->description)
            ]
        ];

        if($this->through)
            $data['user'] = [
                'id' => $this->through->user_id,
                'name' => $this->through->user->name,
                'photo' => $this->through->user->photo
            ];

        return $data;
    }
}
