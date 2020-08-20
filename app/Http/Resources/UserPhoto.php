<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserPhoto extends JsonResource
{
    private static $default_photo = '/defaults/default-user-photo.png';

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
        $default = getClubCode() . self::$default_photo;

        return Storage::disk('images')->url((! empty($photo)) ? $photo : $default);
    }
}
