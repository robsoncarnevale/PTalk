<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CarModelPicture extends JsonResource
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
            'photo' =>  $this->photo,
            'photo_url' => self::get($photo),
        ];
    }

    public static function get($photo)
    {
        if (! empty($photo))
            return Storage::disk('images')->url($photo);

        return null;
    }
}
