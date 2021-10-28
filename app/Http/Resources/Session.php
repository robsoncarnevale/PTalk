<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class Session extends JsonResource
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
        $resource['privileges'] = auth()->user()->privileges->pluck('action');

        $resource['member_class'] = $this->member_class;
        
        return [
            'id' => $resource['id'],
            'name' => $resource['name'],
            'nickname' => $resource['nickname'],
            'email' => $resource['email'],
            'type' => $resource['type'],
            'photo' => $resource['photo'],
            'photo_url' => UserPhoto::get($this->photo),
            'first_name' => $resource['first_name'],
            'last_name' => $resource['last_name'],
            'member_class' => $resource['member_class'],
            'privileges' => $resource['privileges'],
        ];
    }
}
