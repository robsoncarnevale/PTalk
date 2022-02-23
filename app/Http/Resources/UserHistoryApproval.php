<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

// use Storage;

class UserHistoryApproval extends JsonResource
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
            'user' => $this->getUser(),
            'approval_status' => $this->approval_status,
            'reason' => $this->reason,
            'created_by' => $this->getCreatedBy($this->get_created_by),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_at_formatted' => $this->created_at_formatted,
            'updated_at_formatted' => $this->updated_at_formatted,
        ];
    }

    private function getUser(){
        $user = $this->user->toArray();

        $user['phone_formatted'] = $this->formatPhone($user['phone']);
        $user['display_name'] = $this->displayName($user);
        $user['photo'] = $user['photo'];
        $user['photo_url'] = UserPhoto::get('');

        return $user;
    }

    private function getCreatedBy($user){
        if ($user) {
            $user = $user->toArray();
            
            $user['photo'] = $user['photo'];
            $user['photo_url'] = UserPhoto::get('');
        }

        return $user;
    }

    private function formatPhone($phone)
    {
        $formatedPhone = preg_replace('/[^0-9]/', '', $phone);
        $matches = [];
        preg_match('/^([0-9]{2})([0-9]{4,5})([0-9]{4})$/', $formatedPhone, $matches);
        if ($matches) {
            return '('.$matches[1].') '.$matches[2].'-'.$matches[3];
        }
    
        return $phone; // return number without format
    }

    private function displayName($user)
    {
        if (! empty($user['nickname'])) {
            return $user['nickname'];
        }

        $name_exploded = explode(" ", $user['name']);

        $first_name = $name_exploded[0];
        $last_name =  (count($name_exploded) > 1) ? end($name_exploded) : '';

        return trim($first_name . ' ' . $last_name);
    }
}