<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class UserCollection extends ResourceCollection
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
            $resource['data'] = User::collection($resource['data']);
        } else return User::collection($resource);

        return $resource;
    }
}
