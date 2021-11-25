<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BankAccountHistory extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        // return parent::toArray($request);

        $data = $this->json();

        $data['id'] = $this->id;

        $data['operation'] = [
            'code' => $data['operation'],
            'description' => __('bank_account.history.data.operation.' . $data['operation'])
        ];

        $data['operation_type'] = [
            'code' => $data['operation_type'],
            'description' => __('bank_account.history.data.operation_type.' . $data['operation_type'])
        ];

        $data['created_at'] = $this->created_at;
        $data['updated_at'] = $this->updated_at;

        return $data;
    }
}
