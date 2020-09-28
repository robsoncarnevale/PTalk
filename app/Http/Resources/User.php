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

        if (array_key_exists('name', $resource)) {
            $explode_name = explode(" ", $resource['name']);
    
            $resource['first_name'] = $explode_name[0];
            $resource['last_name'] =  (count($explode_name) > 1) ? end($explode_name) : '';
        }

        if (array_key_exists('photo', $resource)) {
            $resource['photo_url'] = UserPhoto::get($resource['photo']);
        }

        if (array_key_exists('indicator', $resource) && is_array($resource['indicator'])) {
            if (array_key_exists('photo', $resource['indicator'])) {
                $resource['indicator']['photo'] = UserPhoto::get($resource['indicator']['photo']);
            }
        }

        if (array_key_exists('vehicles', $resource)) {
            $resource['vehicles'] = Vehicle::collection($this->vehicles);
            // foreach($resource['vehicles'] as $i_vehicle => $vehicle) {
            //     $resource['vehicles'][$i_vehicle]['carplate_formatted'] = $this->vehicles[$i_vehicle]->carplate_formatted;
            // }
        }

        if (array_key_exists('phone', $resource)) {
            $resource['phone_formatted'] = $this->formatPhone($resource['phone']);
        }

        if (array_key_exists('participation_request_information', $resource) && is_array($resource['participation_request_information'])) {
            if (array_key_exists('vehicle_photo', $resource['participation_request_information']) && $resource['participation_request_information']['vehicle_photo']) {
                $resource['participation_request_information']['vehicle_photo'] = Storage::disk('images')->url($resource['participation_request_information']['vehicle_photo']);
            }
        }

        return $resource;

    //     return [
    //         'id' => $this->id,
    //         'name' => $this->name,
    //         "first_name" => "Davi",
    //         "last_name" => "Souto",
    //         'email' => $this->email,
    //         'email_verified_at' => $this->email_everified_at,
    //         'phone' => $this->phone,
    //         'type' => $this->type,
    //         'document_cpf' => $this->document_cpf,
    //         'document_rg' => $this->document_rg,
    //         'photo' => $this->photo,
    //         'photo_url' => UserPhoto::get($this->photo),
    //         'privilege_id' => $this->privilege_id,
    //         'privileges' => $this->privilegesToArray($this->privileges),
    //         'company' => $this->company,
    //         'company_activities' => $this->company_activities,
    //         'comercial_address' => $this->comercial_address,
    //         'home_address' => $this->home_address,
            
    //         'status' => $this->status,
    //         'status_reason' => $this->status_reason,
    //         'suspended_time' => $this->suspended_time,
    //         'approval_status' => $this->approval_status,
    //         'approval_status_date' => $this->approval_status_date,
    //         'deleted' => $this->deleted,

    //         "member_class_id" => $this->member_class_id,
    //         "indicated_by" => $this->indicated_by,
          
    //         'created_at' => $this->created_at,
    //         'updated_at' => $this->updated_at,
    //     ];
    }

    private function privilegesToArray($privileges)
    {
        if (! $privileges) {
            return array();
        }

        return $privileges->toArray();
    }

    private function formatPhone($phone)
    {
        $formatedPhone = preg_replace('/[^0-9]/', '', $phone);
        $matches = [];
        preg_match('/^([0-9]{2})([0-9]{4,5})([0-9]{4})$/', $formatedPhone, $matches);
        if ($matches) {
            return '('.$matches[1].') '.$matches[2].'-'.$matches[3];
        }
    
        return $phone; // return number without format
    }
}