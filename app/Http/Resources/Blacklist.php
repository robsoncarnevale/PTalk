<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Blacklist extends JsonResource
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
            'phone' => $this->phone,
            'phone_formatted' => $this->phone_formatted,
            'description' => $this->description,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'history' => BlacklistHistory::collection($this->whenLoaded('history')),
            // 'history' => $this->getBlacklistHistory(),
        ];
    }

    // private function getBlacklistHistory()
    // {
    //     $history = array();

    //     if ($this->history) {
    //         foreach($this->history as $history) {
    //             $history[] = [
    //                 'id' => $history['id'],
    //                 'status' => $history['status'],
    //                 'description' => $history['description'],
    //                 'changed_by' => [],
    //                 'created_at' => $history['created_at'],
    //             ];
    //         }
    //     }

    //     return $history;
    // }
}