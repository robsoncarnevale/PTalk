<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EventClassData extends JsonResource
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
            'event_id' => $this->event_id,
            'member_class_id' => $this->member_class_id,
            'start_subscription_date' => $this->start_subscription_date,
            'start_subscription_date_br' => $this->start_subscription_date_br,
            'vehicle_value' => $this->vehicle_value,
            'participant_value' => $this->participant_value,
            'companion_value' => $this->companion_value,
            'member_class' => $this->member_class,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
