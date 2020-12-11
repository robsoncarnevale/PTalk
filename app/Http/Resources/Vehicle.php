<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

use Storage;

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
            'carplate_formatted' => $this->carplate_formatted,
            'user' => new User($this->whenLoaded('user')),
            'car_model' => new CarModel($this->car_model),
            'car_color' => new CarColor($this->car_color),
            'year_manufacture' => $this->year_manufacture,
            'model_year' => $this->model_year,
            'document_renavam' => $this->document_renavam,
            'chassis' => $this->chassis,
            'photos' => $this->photosToArray($this->photos),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    public static function photosToArray($photos)
    {
        $return_photos = array();

        if (empty($photos)) {
            return array();
        }

        foreach($photos->sortByDesc('id') as $photo) {
            $return_photos[] = [
                'id' => $photo->id,
                'photo' => Storage::disk('images')->url($photo->photo),
            ];
        }

        return $return_photos;
    }
}