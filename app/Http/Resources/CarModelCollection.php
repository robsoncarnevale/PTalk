<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CarModelCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $resource = parent::toArray($this->collection);

        if (array_key_exists('data', $resource))
        {
            $resource['data'] = CarModel::collection($resource['data']);
        } else return CarModel::collection($resource);

        return $resource;
    }
}
