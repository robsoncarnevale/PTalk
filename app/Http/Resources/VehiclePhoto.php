<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class VehiclePhoto extends JsonResource
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
            'photo' => $this->getPhotoUrl($this->photo),
            'vehicle_id' => $this->vehicle_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    private function getPhotoUrl($photo)
    {
        if (! empty($photo)) {
            return Storage::disk('images')->url($photo);
        }

        return false;
    }
}
