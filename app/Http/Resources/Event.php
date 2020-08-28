<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class Event extends JsonResource
{
    private static $default_cover_picture = '/defaults/default-event-picture.png';

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
            'name' => $this->name,
            'description' => $this->description,
            'address' => $this->address,
            'meeting_point' => $this->meeting_point,
            'date' => $this->date,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'max_vehicles' => $this->max_vehicles,
            'max_participants' => $this->max_participants,
            'max_companions' => $this->max_companions,
            'cover_picture' => $this->getCoverPicture($this->cover_picture),
            'created_by' => $this->userArray($this->user),
            'status' => $this->status,
        ];
    }

    private function userArray($user)
    {
        return [
            'id' => $this->user->id,
            'name' => $this->user->name,
            'photo' => UserPhoto::get($this->user->photo),
            'email' => $this->user->email,
            'company' => $this->user->company,
            'status' => $this->user->status,
        ];
    }

    public static function getCoverPicture($photo)
    {
        $default = getClubCode() . self::$default_cover_picture;

        return Storage::disk('images')->url((! empty($photo)) ? $photo : $default);
    }
}
