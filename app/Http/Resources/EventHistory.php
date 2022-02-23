<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EventHistory extends JsonResource
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
            'status' => $this->status,
            'resume' => $this->getResume($this->resume),
            'effected_by' => $this->getEffectedBy($request),
            'created_at' => $this->created_at,
            'created_at_formatted' => $this->created_at_formatted,
        ];
    }

    private function getEffectedBy($request)
    {
        if (empty($this->effected_by)) {
            return [
                'id' => 0,
                'name' => 'Sistema',
                'nickname' => 'Sistema',
                'display_name' => 'Sistema',
                'photo' => false,
            ];
        }

        return [
            'id' => $this->getEffectedBy->id,
            'name' => $this->getEffectedBy->name,
            'nickname' => $this->getEffectedBy->nickname,
            'display_name' => $this->getEffectedBy->display_name,
            'photo_url' => UserPhoto::get(''),
            'photo' => $this->getEffectedBy->photo
        ];
    }

    private function getResume($resume){
        if ($resume) {
            try {
                $resume = json_decode($resume, true);
       
                if (array_key_exists('event', $resume)) {
                    if (array_key_exists('cover_picture', $resume['event'])) {
                        $resume['event']['cover_picture'] = Event::getCoverPicture($resume['event']['cover_picture']);
                    }
                }

                if (array_key_exists('old_event', $resume)) {
                    if (array_key_exists('cover_picture', $resume['old_event'])) {
                        $resume['old_event']['cover_picture'] = Event::getCoverPicture($resume['old_event']['cover_picture']);
                    }
                }
            } catch(\Exception $e) {
                return $resume;
            }
    
            return json_encode($resume);
        }
    }
}