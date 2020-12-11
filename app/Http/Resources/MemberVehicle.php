<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MemberVehicle extends JsonResource
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
            'id' => $this->id,
            'car_model' => new CarModel($this->car_model),
            'car_color' => new CarColor($this->car_color),
            'year_manufacture' => $this->year_manufacture,
            'model_year' => $this->model_year,
            'photos' => Vehicle::photosToArray($this->photos),
            // 'document_renavam' => $this->document_renavam,
            // 'chassis' => $this->chassis,
        ];
    }
}