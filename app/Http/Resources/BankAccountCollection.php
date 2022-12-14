<?php

namespace App\Http\Resources;

// use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class BankAccountCollection extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {;
        return [
            'data' => [
                'resume' => $this['resume'],
                'accounts' => BankAccount::collection($this['accounts']['data']),
            ],
            'paginator' => $this['accounts']['paginator'],
        ];
    }
}
