<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

use App\Models\User;
use App\Models\EventHistory;
use App\Models\MemberClass;
use App\Models\EventClassData;

use App\Http\Resources\Event as EventResource;


use Storage;

/**
 * Event Model
 *
 * @author Davi Souto
 * @since 20/08/2020
 */
class Event extends Model
{
    protected $table = 'events';

    const ACTIVE_STATUS = 'active';
    const INACTIVE_STATUS = 'inactive';
    const DRAFT_STATUS = 'draft';
    const CLOSED_STATUS = 'closed';
    const REALIZED_STATUS = 'realized';
    const CANCELLED_STATUS = 'cancelled';


    protected $fillable = [
        'name',
        'description',
        'meeting_point',
        'date',
        'date_limit',
        'start_time',
        'end_time',
        'max_vehicles',
        'max_participants',
        'max_companions',
        'status',
    ];

    protected $hidden = [
        'club_code',
    ];

    /**
     * Get the user
     */
    public function user()
    {
        return $this->hasOne('App\Models\User', 'id', 'created_by');
    }

    /**
     * Get class data
     */
    public function class_data()
    {
        return $this->hasMany('App\Models\EventClassData');
    }

    /**
     * Get event history
     */
    public function history()
    {
        return $this->hasMany('App\Models\EventHistory');
    }

    /**
     * Get event address
     */
    public function address()
    {
        return $this->hasOne('App\Models\EventAddress');
    }

    /////////////////////////

    /**
     * Upload event cover picture
     * 
     * @param \Illuminate\Http\File $file
     * @author Davi Souto
     * @since 17/05/2021
     */
    public function upload($file)
    {
        $upload_photo = Storage::disk('images')->putFile(getClubCode().'/events', $file);

        if ($upload_photo)
        {
            if (! empty($this->cover_picture) && Storage::disk('images')->exists($this->cover_picture)) {
                Storage::disk('images')->delete($this->cover_picture);
            }
            
            $this->cover_picture = $upload_photo;
        }

        return $this;
    }

    /**
     * Save event history
     * 
     * @param json $old_data
     * @author Davi Souto
     * @since 17/05/2021
     */
    public function saveHistory($old_data, $request)
    {
        $history = new EventHistory();
        $history->club_code = getClubCode();
        $history->effected_by = User::getAuthenticatedUserId();
        $history->event_id = $this->id;

        // First register
        if (! $old_data) {
            $address = false;

            // Address
            if ($request->get('event_address_zip_code')) {
                $address = $request->get('event_address_street_address');

                if ($request->get('event_address_number')) {
                    $address .= ', ' . $request->get('event_address_number');
                }

                if ($request->get('event_address_complement')) {
                    $address .= ', ' . $request->get('event_address_complement');
                }

                $address .= " - " . $request->get('event_address_neighborhood');
                $address .= " - " . $request->get('event_address_city');
                $address .= " - " . $request->get('event_address_state');
            }

            $history->resume = json_encode([
                'event' => array_merge($this->toArray(), [ 'address' => $address ]),
                'class' => array_key_exists('class', $request->all()) ? $request->all()['class'] : [],
            ]);
            

            $history->status = Event::DRAFT_STATUS;
        } else {
            $actual_data_event = $this->toArray();
            $actual_class_data = $request->get('class');

            $diff_event = array();
            $diff_class = array();

            $address = false;
            $old_address = false;

            // Address
            if ($request->get('event_address_zip_code')) {
                $address = $request->get('event_address_street_address');

                if ($request->get('event_address_number')) {
                    $address .= ', ' . $request->get('event_address_number');
                }

                if ($request->get('event_address_complement')) {
                    $address .= ', ' . $request->get('event_address_complement');
                }

                $address .= " - " . $request->get('event_address_neighborhood');
                $address .= " - " . $request->get('event_address_city');
                $address .= " - " . $request->get('event_address_state');
            }

            // Old Address
            if ($old_data['event']['address']) {
                $old_address = $old_data['event']['address']['street_address'];

                if ($old_data['event']['address']['number']) {
                    $old_address .= ', ' . $old_data['event']['address']['number'];
                }

                if ($old_data['event']['address']['complement']) {
                    $old_address .= ', ' . $old_data['event']['address']['complement'];
                }

                $old_address .= " - " . $old_data['event']['address']['neighborhood'];
                $old_address .= " - " . $old_data['event']['address']['city'];
                $old_address .= " - " . $old_data['event']['address']['state'];
            }

            // Clean event date
            foreach(['date', 'date_limit'] as $date_field) {
                if (array_key_exists($date_field, $old_data['event'])) {
                    $old_data['event'][$date_field] = substr($old_data['event'][$date_field], 0, 10);
                }
            }
            // Clean class date
            if (array_key_exists('class', $old_data)) {
                foreach($old_data['class'] as $i_old_class => $v_old_class) {
                    if (array_key_exists('start_subscription_date', $v_old_class)) {
                        $old_data['class'][$i_old_class]['start_subscription_date']  = substr($v_old_class['start_subscription_date'], 0, 10);
                    }
                }
            }
     
            foreach($actual_class_data as $i_actual_class_data => $v_actual_class_data) {
                // $actual_class_data[$i_actual_class_data] = $v_actual_class_data->toArray();

                // $actual_class_data[$i_actual_class_data] = [
                //     // 'event_id' => $actual_class_data[$i_actual_class_data]['event_id'],
                //     // 'member_class_id' => $actual_class_data[$i_actual_class_data]['member_class_id'],
                //     'start_subscription_date' => $actual_class_data[$i_actual_class_data]['start_subscription_date'],
                //     'vehicle_value' => $actual_class_data[$i_actual_class_data]['vehicle_value'],
                //     'participant_value' => $actual_class_data[$i_actual_class_data]['participant_value'],
                //     'companion_value' => $actual_class_data[$i_actual_class_data]['companion_value'],
                //     // 'created_at' => $actual_class_data[$i_actual_class_data]['created_at'],
                //     // 'updated_at' => $actual_class_data[$i_actual_class_data]['updated_at'],
                // ];


                if (array_key_exists($i_actual_class_data, $old_data['class'])) {
                    $diff_class[$i_actual_class_data] = array_diff($actual_class_data[$i_actual_class_data], $old_data['class'][$i_actual_class_data]);
                }
            }
            
            unset($actual_data_event['class_data']);

            if ($address != $old_address) {
                $diff_event = array_diff(array_merge($actual_data_event, [ 'address' => $address ]), array_merge($old_data['event'], [ 'address' => $old_address]));
            } else {
                if (isset($actual_data_event['address'])) {
                    unset($actual_data_event['address']);
                }

                if (isset($old_data['event']['address'])) {
                    unset($old_data['event']['address']);
                }

                $diff_event = array_diff($actual_data_event, $old_data['event']);
            }

            $history->resume = json_encode([
                'event' => $diff_event,
                'old_event' => $old_data['event'],
                'class' => $diff_class,
                'old_class' => array_key_exists('class', $old_data) ? $old_data['class'] : [],
            ]);

            $history->status = $this->status;
        }

        $history->save();
    }

    public function saveClassData($classes)
    {
        if (is_array($classes)) {
            $member_classes = array();

            foreach($classes as $class_name => $class_data) {
                if (! array_key_exists($class_name, $member_classes)) {       
                    $find_class = MemberClass::select()
                        ->where('club_code', getClubCode())
                        ->where('label', $class_name)
                        ->first();

                    $member_classes[$class_name] = $find_class;
                }

                $class = $member_classes[$class_name];

                $event_class = EventClassData::select()
                    ->where('club_code', getClubCode())
                    ->where('event_id', $this->id)
                    ->where('member_class_id', $class->id)
                    ->first();

                if (! $event_class) {
                    $event_class = new EventClassData();
                }

                $event_class->club_code = getClubCode();
                $event_class->event_id = $this->id;
                $event_class->member_class_id = $class->id;

                $event_class->fill($class_data);

                if (! empty($class_data['start_subscription_date'])) {
                    $event_class->start_subscription_date = dateBrToDatabase($class_data['start_subscription_date']);
                } else $event_class->start_subscription_date = null;

                $event_class->save();

            }
        }

        return $classes;
    }

    function getDateAttribute($date)
    {
        if ($date) {
            return (new Carbon($date))->format('Y-m-d');
        }
        
        return null;
    }

    function getDateBrAttribute()
    {
        if ($this->date) {
            return (new Carbon($this->date))->format('d/m/Y');
        }
        
        return null;
    }

    function getDateLimitAttribute($date)
    {
        if ($date) {
            return (new Carbon($date))->format('Y-m-d');
        }
        
        return null;
    }

    function getDateLimitBrAttribute()
    {
        if ($this->date_limit) {
            return (new Carbon($this->date_limit))->format('d/m/Y');
        }
        
        return null;
    }
}
