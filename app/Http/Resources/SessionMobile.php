<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SessionMobile extends JsonResource
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

        $explode_name = explode(" ", $resource['name']);

        $resource['first_name'] = $explode_name[0];
        $resource['last_name'] =  (count($explode_name) > 1) ? end($explode_name) : '';
        $resource['privileges'] = \App\Models\HasPrivilege::select('privilege_action')
            ->where('privilege_group_id', $resource['privilege_id'])
            ->get()
            ->pluck('privilege_action');

        $resource['vehicles_count'] = \App\Models\Vehicle::where('user_id', $resource['id'])->where('deleted', false)->count();
        $resource['events_count'] = 0;
        $resource['club_code'] = $request->get('club_code');
        
        return [
            'id' => $resource['id'],
            'name' => $resource['name'],
            'email' => $resource['email'],
            'photo' => $resource['photo'],
            'photo_url' => UserPhoto::get($this->photo),
            'privilege_id' => $resource['privilege_id'],
            'company' => $resource['company'],
            'first_name' => $resource['first_name'],
            'last_name' => $resource['last_name'],
            'privileges' => $resource['privileges'],
            'vehicles_count' => $resource['vehicles_count'],
            'events_count' => $resource['events_count'],
            'club_code' => $resource['club_code'],
        ];
    }
}