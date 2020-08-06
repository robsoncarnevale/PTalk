<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class CarModel extends JsonResource
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
        

        if (array_key_exists('picture', $resource))
        {
            $resource['picture_url'] = null;

            if (! empty($resource['picture']))
                $resource['picture_url'] = Storage::disk('images')->url($resource['picture']);
        }

        return $resource;
    }
}
