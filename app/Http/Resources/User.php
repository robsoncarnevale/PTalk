<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class User extends JsonResource
{
    private $default_photo = '/defaults/default-user-photo.png';

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // $default = getClubCode() . $this->default_photo;

        // $resource = parent::toArray($request);

        // if (array_key_exists('photo', $resource))
        //     $resource['photo_url'] = Storage::disk('images')->url((! empty($resource['photo'])) ? $resource['photo'] : $default);

        // return $resource;

        $resource = parent::toArray($request);

        $explode_name = explode(" ", $resource['name']);

        $resource['first_name'] = $explode_name[0];
        $resource['last_name'] =  (count($explode_name) > 1) ? end($explode_name) : '';
        $resource['photo_url'] = UserPhoto::get($resource['photo']);

        return $resource;

        // return [
        //     'id' => $resource['id'],
        //     'name' => $resource['name'],
        //     'email' => $resource['email'],
        //     'phone' => $resource['phone'],
        //     'type' => $resource['type'],
        //     'document_cpf' => $resource['document_cpf'],
        //     'document_rg' => $resource['document_rg'],
        //     'photo' => $resource['photo'],
        //     'photo_url' => UserPhoto::get($this->photo),
        //     'privilege_id' => $resource['privilege_id'],
        //     'first_name' => $resource['first_name'],
        //     'last_name' => $resource['last_name'],
        //     'company' => $resource['company'],
        //     'company_activities' => $resource['company_activities'],
        //     'comercial_address' => $resource['comercial_address'],
        //     'home_address' => $resource['home_address'],
            
        //     'status' => $resource['status'],
        //     'status_reason' => $resource['status_reason'],
        //     'suspended_time' => $resource['suspended_time'],
        //     'approval_status' => $resource['approval_status'],
        //     'approval_status_date' => $resource['approval_status_date'],
        //     'deleted' => $resource['deleted'],
          
        //     'created_at' => $resource['created_at'],
        //     'updated_at' => $resource['updated_at'],
        // ];
    }
}
