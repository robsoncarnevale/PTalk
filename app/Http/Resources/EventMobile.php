<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

use App\Models\EventSubscription;
use App\Models\MemberClass;
use App\Models\User;

class EventMobile extends JsonResource
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

            'address' => $this->address ? new EventAddress($this->address) : false,
            'class_data' => $this->mapClassData(EventClassData::collection($this->class_data)),
            'has_subscripted' => $this->checkSubscripted(),
            'my_subscription' => $this->mySubscription(),
            'subscription' => $this->getSubscription(),
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

    private function mySubscription()
    {
        $subscription = EventSubscription::select()
            ->where('club_code', getClubCode())
            ->where('user_id', User::getAuthenticatedUserId())
            ->where('event_id', $this->id)
            ->first();

        return $subscription;
    }

    private function getSubscription()
    {
        $subscriptions = EventSubscription::select()
            ->where('club_code', getClubCode())
            ->where('event_id', $this->id)
            ->where('status', EventSubscription::ACTIVE_STATUS)
            ->count();

        $vehicles = EventSubscription::select()
            ->where('club_code', getClubCode())
            ->where('event_id', $this->id)
            ->where('status', EventSubscription::ACTIVE_STATUS)
            ->where('vehicle', true)
            ->count();

        $companions = EventSubscription::select()
            ->where('club_code', getClubCode())
            ->where('event_id', $this->id)
            ->where('status', EventSubscription::ACTIVE_STATUS)
            ->sum('companions');

        $amount = EventSubscription::select()
            ->where('club_code', getClubCode())
            ->where('event_id', $this->id)
            ->where('status', EventSubscription::ACTIVE_STATUS)
            ->sum('amount');

        return [
            'participants' => $subscriptions,
            'vehicles' => $vehicles,
            'companions' => $companions,
            'amount' => $amount,
        ];
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

    public static function mapHistory($history_data)
    {
        $result = $history_data;

        foreach($history_data as $i_history => $history) {
            $resume = json_decode($history->resume, true);

            if (array_key_exists('old_event', $resume) && array_key_exists('address', $resume['old_event']) && ! empty($resume['old_event']['address']) && is_array($resume['old_event']['address'])) {
                $address = $resume['old_event']['address']['street_address'];

                if ($resume['old_event']['address']['number']) {
                    $address .= ', ' . $resume['old_event']['address']['number'];
                }

                if ($resume['old_event']['address']['complement']) {
                    $address .= ', ' . $resume['old_event']['address']['complement'];
                }

                $address .= " - " . $resume['old_event']['address']['neighborhood'];
                $address .= " - " . $resume['old_event']['address']['city'];
                $address .= " - " . $resume['old_event']['address']['state'];

                $resume['old_event']['address'] = $address;
                $result[$i_history]['resume'] = json_encode($resume);
            }
        }

        return $result;
    }
}
