<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserWaitingApproval extends JsonResource
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

        if (isset($this->display_name)) {
            $resource['display_name'] = $this->display_name;
        }

        if (! array_key_exists('vehicles_count', $resource)) {
            $resource['vehicles_count'] = \App\Models\Vehicle::select('id', 'user_id')
                ->where('user_id', $this->id)
                ->where('deleted', false)
                ->count();
        }

        if (array_key_exists('approval_history', $resource)) {
            $resource['approval_history'] = $this->approval_history->sortByDesc('created_at');
        }

        return $resource;
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