<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Vehicle extends JsonResource
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

        if (array_key_exists('user', $resource))
            $resource['user'] = new User($resource['user']);

        if (array_key_exists('car_model', $resource))
            $resource['car_model'] = new CarModel($resource['car_model']);

        return $resource;
    }
}
