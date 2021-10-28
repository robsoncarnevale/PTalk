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

        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'nickname' => $this->nickname,
            'document_cpf' => $this->document_cpf,
            'document_rg' => $this->document_rg,
            'phone' => $this->phone,
            'email' => $this->email,
            'privilege_id' => $this->privilege_id,
            'photo' => $this->photo,
            'photo_url' => UserPhoto::get($this->photo),
            'first_name' => $resource['first_name'],
            'last_name' => $resource['last_name'],
            'company' => $this->company,
            'company_activities' => $this->company_activities,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'approval_status_date' => $this->approval_status_date,
            // 'status' => $this->status,
            'type' => $this->type,
            'member_class' => $this->member_class,
            'privilege_group' => $this->privilege_group,
            'vehicles' => MemberVehicle::collection($this->vehicles)
        ];

        if($this->bank_account)
            $data['bank_account'] = $this->bank_account->id;

        return $data;
    }
}
