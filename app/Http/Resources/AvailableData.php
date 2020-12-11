<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AvailableData extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'states' => $this['states'],
            'cities' => $this['cities'],
            'car_models' => $this['car_models'],
            'car_colors' => $this['car_colors'],
        ];
    }
}