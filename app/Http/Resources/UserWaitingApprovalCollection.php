<?php

namespace App\Http\Resources;

// use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class UserWaitingApprovalCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => UserWaitingApproval::collection($this['data']),
            'paginator' => $this['paginator'],
        ];
    }
}
