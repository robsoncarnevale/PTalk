<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

use App\Models\EventSubscription;
use App\Models\MemberClass;
use App\Models\User;

class Event extends JsonResource
{
    private static $default_cover_picture = '/defaults/default-event-picture.png';

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
            'name' => $this->name,
            'description' => $this->description,
            'address' => $this->address,
            'meeting_point' => $this->meeting_point,
            'date' => $this->date,
            'date_br' => $this->date_br,
            'date_limit' => $this->date_limit,
            'date_limit_br' => $this->date_limit_br,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'max_vehicles' => $this->max_vehicles,
            'max_participants' => $this->max_participants,
            'max_companions' => $this->max_companions,
            'cover_picture' => $this->getCoverPicture($this->cover_picture),
            'created_by' => $this->userArray($this->user),
            'status' => $this->status,

            'class_data' => $this->mapClassData(EventClassData::collection($this->class_data)),
            'history' => EventHistory::collection($this->history->sortByDesc('created_at')),
            'has_subscripted' => $this->checkSubscripted(),
            // 'history' => EventHistory::collection($this->whenLoaded('history')),
        ];
    }

    private function userArray($user)
    {
        return [
            'id' => $this->user->id,
            'name' => $this->user->name,
            'photo' => UserPhoto::get($this->user->photo),
            'email' => $this->user->email,
            'company' => $this->user->company,
            'status' => $this->user->status,
        ];
    }

    /**
     * Check if has subscripted in event
     * 
     * @return boolean
     * @author Davi Souto
     * @since 24/05/2021
     */
    private function checkSubscripted()
    {
        $check_subscription = EventSubscription::select()
            ->where('club_code', getClubCode())
            ->where('user_id', User::getAuthenticatedUserId())
            ->where('event_id', $this->id)
            ->first();

        if ($check_subscription) {
            return true;
        }

        return false;
    }

    public static function getCoverPicture($photo)
    {
        $default = getClubCode() . self::$default_cover_picture;

        return Storage::disk('images')->url((! empty($photo)) ? $photo : $default);
    }

    public static function mapClassData($class_data)
    {
        $result_class_data = array();

        foreach($class_data as $v_class_data){
            if (! is_object($v_class_data)) {
                $v_class_data = (object) $v_class_data;
            }

            $member_class = MemberClass::select()
                ->where('club_code', getClubCode())
                ->where('id', $v_class_data->member_class_id)
                ->first();

            $result_class_data[$member_class->label] = $v_class_data;
        }

        return $result_class_data;
    }
}
