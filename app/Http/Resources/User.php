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
        $default = getClubCode() . $this->default_photo;

        $resource = parent::toArray($request);

        if (array_key_exists('photo', $resource))
            $resource['photo_url'] = Storage::disk('images')->url((! empty($resource['photo'])) ? $resource['photo'] : $default);

        return $resource;
    }
}
