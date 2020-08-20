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
        return [
            'id' => $this->id,
            'carplate' => $this->carplate,
            'user' => new User($this->whenLoaded('user')),
            'car_model' => new CarModel($this->car_model),
            'car_color' => new CarColor($this->car_color),
            'year_manufacture' => $this->year_manufacture,
            'model_year' => $this->model_year,
            'document_renavam' => $this->document_renavam,
            'chassis' => $this->chassis,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}