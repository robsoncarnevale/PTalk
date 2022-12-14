<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\EventRequest;
use App\Http\Requests\SubscribeEventRequest;
use App\Http\Requests\UnsubscribeEventRequest;


use App\Models\AccountLaunch;
use App\Models\ClubBankAccount;
use App\Models\ClubLaunch;
use App\Models\Event;
use App\Models\EventSubscription;
use App\Models\MemberClass;
use App\Models\User;

use App\Http\Resources\Event as EventResource;
use App\Http\Resources\EventMember as EventMemberResource;
use App\Http\Resources\EventCollection;

use DB;
use Exception;
use Storage;

class EventsController extends Controller
{
    protected $only_admin = false;

    /**
     * List events
     * @author Davi Souto
     * @since 24/08/2020
     */
    public function List(EventRequest $request)
    {
        $events = Event::select()
            ->where('club_code', getClubCode())
            ->where('deleted', false)
            ->orderBy('id', 'desc')
            ->jsonPaginate(20);

        return response()->json([ 'status' => 'success', 'data' => (new EventCollection($events)) ]);
    }

    /**
     * Get event
     * @author Davi Souto
     * @since 24/08/2020
     */
    public function Get(Event $event, EventRequest $request)
    {
        $this->validateClub($event->club_code, 'event');

        return response()->json([ 'status' => 'success', 'data' => (new EventResource($event)) ]);
    }

    /**
     * Create event
     * @author Davi Souto
     * @since 24/08/2020
     */
    public function Create(EventRequest $request)
    {
        DB::beginTransaction();

        try {
            $event = new Event();
            $data = $request->all();

            foreach($data as $i_param => $param) {
                if (substr($i_param, 0, 6) == 'class_') {
                    $init_class = substr($i_param, 6);
                    
                    $class_param = substr($init_class, 0, strpos($init_class, '_'));
                    $class_value = substr($init_class, strpos($init_class, '_')+1);
                    
                    if (! array_key_exists('class', $data)) {
                        $data['class'] = array();
                    }

                    if (! array_key_exists($class_param, $data['class'])) {
                        $data['class'][$class_param] = array();
                    }

                    $data['class'][$class_param][$class_value] = $param;

                    unset($data[$i_param]);
                }
            }      
    
            $event->fill($data);
            $event->status = Event::DRAFT_STATUS;

            if ($request->has('max_vehicles') && ! empty($request->get('max_vehicles'))) {
                $event->max_vehicles = preg_replace("#[^0-9]#is", "", $request->get('max_vehicles'));
            }

            if ($request->has('max_participants') && ! empty($request->get('max_participants'))) {
                $event->max_participants = preg_replace("#[^0-9]#is", "", $request->get('max_participants'));
            }

            if ($request->has('max_companions') && ! empty($request->get('max_companions'))) {
                $event->max_companions = preg_replace("#[^0-9]#is", "", $request->get('max_companions'));
            }

            if ($request->has('status')) {
                $event->status = $request->get('status');
            }

            if ($request->has('retention_value')) {
                $event->retention_value = str_replace(",", ".", preg_replace("/[\.]/is", "", $request->get('retention_value')));
            
                if (empty($event->retention_value)) {
                    $event->retention_value = 0.00;
                }
            }

            if (! empty($request->get('date'))) {
                $event->date = dateBrToDatabase($request->date);
            } else $event->date = null;

            if (! empty($request->get('date_limit'))) {
                $event->date_limit = dateBrToDatabase($request->date_limit);
            } else $event->date_limit = null;

            if (! empty($request->get('unsubscribe_date_limit'))) {
                $event->unsubscribe_date_limit = dateBrToDatabase($request->unsubscribe_date_limit);
            } else $event->unsubscribe_date_limit = null;

            $event->club_code = getClubCode();
            $event->name = ucwords($event->name);
            $event->created_by = User::getAuthenticatedUserId();

            // Photo upload
            if ($request->has('cover_picture')) {
                $event->upload($request->file('cover_picture'));
            }

            $event->save();
            $event->saveHistory(false, collect($data));
            $event->saveClassData($data['class']);

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            return response()->json([ 'status' => 'error', 'message' => __('events.error-create', [ 'error' => $e->getMessage() ]) ]);
        }

        return response()->json([ 'status' => 'success', 'data' => (new EventResource($event)), 'message' => __('events.success-create') ]);
    }

    /**
     * Update event
     * @author Davi Souto
     * @since 24/08/2020
     */
    public function Update(Event $event, EventRequest $request)
    {
        DB::beginTransaction();

        try {
            $old_data = array();
            $old_data['event'] = $event->toArray();
            $old_data['event']['address'] = $event->address ? $event->address->toArray() : false;

            $data = $request->all();

            foreach($data as $i_param => $param) {
                if (substr($i_param, 0, 6) == 'class_') {
                    $init_class = substr($i_param, 6);
                    
                    $class_param = substr($init_class, 0, strpos($init_class, '_'));
                    $class_value = substr($init_class, strpos($init_class, '_')+1);
                    
                    if (! array_key_exists('class', $data)) {
                        $data['class'] = array();
                    }

                    if (! array_key_exists($class_param, $data['class'])) {
                        $data['class'][$class_param] = array();
                    }

                    $data['class'][$class_param][$class_value] = $param;

                    unset($data[$i_param]);
                }
            }

            if (array_key_exists('class', $data)) {
                $old_data['class'] = EventResource::mapClassData($event->class_data->toArray());
            
                foreach($old_data['class'] as $i_old_data_class => $v_old_data_class) {
                    $old_data['class'][$i_old_data_class] = (array) $v_old_data_class;
                }
            }

            $event->fill($data);

            if ($request->has('max_vehicles') && ! empty($request->get('max_vehicles'))) {
                $event->max_vehicles = preg_replace("#[^0-9]#is", "", $request->get('max_vehicles'));
            }

            if ($request->has('max_participants') && ! empty($request->get('max_participants'))) {
                $event->max_participants = preg_replace("#[^0-9]#is", "", $request->get('max_participants'));
            }

            if ($request->has('max_companions') && ! empty($request->get('max_companions'))) {
                $event->max_companions = preg_replace("#[^0-9]#is", "", $request->get('max_companions'));
            }

            if ($request->has('status')) {
                $event->status = $request->get('status');
            }

            if ($request->has('retention_value')) {
                $event->retention_value = str_replace(",", ".", preg_replace("/[\.]/is", "", $request->get('retention_value')));
            
                if (empty($event->retention_value)) {
                    $event->retention_value = 0.00;
                }
            }

            if (! empty($request->get('date'))) {
                $event->date = dateBrToDatabase($request->date);
            }

            if (! empty($request->get('date_limit'))) {
                $event->date_limit = dateBrToDatabase($request->date_limit);
            }

            if (! empty($request->get('unsubscribe_date_limit'))) {
                $event->unsubscribe_date_limit = dateBrToDatabase($request->unsubscribe_date_limit);
            }

            $event->name = ucwords($event->name);

            // Photo remove and upload
            if ($request->has('remove_cover_picture') && $request->get('remove_cover_picture') == 'true')
            {
                // if (! empty($event->cover_picture) && Storage::disk('images')->exists($event->cover_picture)) {
                //     Storage::disk('images')->delete($event->cover_picture);
                // }

                $event->cover_picture = null;
            } else if ($request->has('cover_picture'))
            {
                $event->upload($request->file('cover_picture'));
            }

            $event->save();
            $event->saveHistory($old_data, collect($data));
            $event->saveClassData($data['class']);


            DB::commit();
        } catch(Exception $e) {
            DB::rollback();

            return response()->json([ 'status' => 'error', 'message' => __('events.error-update', [ 'error' => $e->getMessage() ]) ]);
        }

        // Get event updated
        $_event = Event::select()
            ->where('id', $event->id)
            ->with('class_data')
            ->first();
        
        return response()->json([ 'status' => 'success', 'data' => (new EventResource($_event)), 'message' => __('events.success-update') ]);
    }

    /**
     * Delete event
     * @author Davi Souto
     * @since 24/08/2020
     */
    public function Delete(Event $event, EventRequest $request)
    {
        $this->validateClub($event->club_code, 'event');

        if (! in_array($event->status, [ Event::DRAFT_STATUS ])) {
            return response()->json([ 'status' => 'error', 'message' => __('events.error-delete-not-draft') ]);
        }

        $event->deleted = true;
        $event->save();

        return response()->json([ 'status' => 'success', 'data' => (new EventResource($event)) ]);
    }

    /**
     * Start event
     * 
     * @param \App\Models\Event $event
     * @author Davi Souto
     * @since 20/05/2021
     */
    public function Start(Event $event)
    {
        $this->validateClub($event->club_code, 'event');

        $member_classes = MemberClass::select()
            ->where('club_code', getClubCode())
            ->get()
            ->toArray();

        $fields = array();
        $fields_lang = array();

        if (! $event->name) {
            $fields[] = 'name';
        }

        if (! $event->description) {
            $fields[] = 'description';
        }

        if (! $event->address) {
            $fields[] = 'address';
        }

        if (! $event->meeting_point) {
            $fields[] = 'meeting_point';
        }

        if (! $event->date) {
            $fields[] = 'date';
        }

        if (! $event->name) {
            $fields[] = 'date_limit';
        }

        if (! $event->start_time) {
            $fields[] = 'start_time';
        }

        if (! $event->end_time) {
            $fields[] = 'end_time';
        }

        if (! $event->max_vehicles) {
            $fields[] = 'max_vehicles';
        }

        if (! $event->max_participants) {
            $fields[] = 'max_participants';
        }

        if (! $event->max_companions) {
            $fields[] = 'max_companions';
        }

        $check_class_fields = [];

        foreach($member_classes as $i_member_class => $member_class){
            $label_class = $member_class['label'];

            if (! array_key_exists($label_class, $check_class_fields)) {
                $check_class_fields[$label_class] = array();
            }

            $check_class_fields[$label_class]['start_subscription_date'] = 'class.' . $label_class . '.start_subscription_date';
            $check_class_fields[$label_class]['vehicle_value'] = 'class.' . $label_class . '.vehicle_value';
            $check_class_fields[$label_class]['participant_value'] = 'class.' . $label_class . '.participant_value';
            $check_class_fields[$label_class]['companion_value'] = 'class.' . $label_class . '.companion_value';

            foreach($event->class_data as $i_class => $class){
                if (! empty($class['start_subscription_date'])) {
                    unset($check_class_fields[$label_class]['start_subscription_date']);
                }

                if (! empty($class['vehicle_value'])) {
                    unset($check_class_fields[$label_class]['vehicle_value']);
                }

                if (! empty($class['participant_value'])) {
                    unset($check_class_fields[$label_class]['participant_value']);
                }

                if (! empty($class['companion_value'])) {
                    unset($check_class_fields[$label_class]['companion_value']);
                }
            }
        }

        foreach($fields as $field) {
            $fields_lang[] = __('events.fields.' . $field);
        }

        foreach($check_class_fields as $label_class => $check_field_arr) {
            foreach($check_field_arr as $check_field) {
                $check_field = substr($check_field, strpos($check_field, '.')+1);
                $check_field = substr($check_field, strpos($check_field, '.')+1);

                $fields_lang[] = __('events.fields.class.' . $check_field) . " (" . ucfirst($label_class) . ")";
            }
        }

        if (! empty($fields_lang)){
            return response()->json([ 'status' => 'error', 'message' => __('events.error-start-event', [ 'name' => $event->name, 'fields' =>  implode("\n", $fields_lang) ]) ]);
        }

        $event->status = Event::ACTIVE_STATUS;
        $event->save();

        return response()->json([ 'status' => 'success', 'data' => (new EventResource($event)), 'message' => __('events.success-start-event', [ 'name' => $event->name ]) ]);
    }

    /**
     * Subscribe in event
     * 
     * @author Davi Souto
     * @since 24/05/2021
     */
    public function Subscribe(Event $event, SubscribeEventRequest $request)
    {
        $this->validateClub($event->club_code, 'event');

        $check_subscription = EventSubscription::select()
            ->where('club_code', getClubCode())
            ->where('user_id', User::getAuthenticatedUserId())
            ->where('event_id', $event->id)
            ->where('status', EventSubscription::ACTIVE_STATUS)
            ->first();
        
        // Check if you are already registered for the event
        if ($check_subscription){
            return response()->json([ 'status' => 'error', 'message' => __('events.error-subscribe-event.already-subscribted', [ 'name' => $event->name ]) ]);
        }

        // Check if event is active
        if ($event->status !== Event::ACTIVE_STATUS) {
            return response()->json([ 'status' => 'error', 'message' => __('events.error-subscribe-event.status', [ 'name' => $event->name ]) ]);
        }

        // Get max spaces
        $max_participants = $event->max_participants;
        $max_companions = $event->max_companions;
        $max_vehicles = $event->max_vehicles;

        $subscript_vehicle = $request->get('vehicle');

        if ($subscript_vehicle === true || $subscript_vehicle == 'true' || $subscript_vehicle == 1) {
            $subscript_vehicle = true;
        } else $subscript_vehicle = false;
        
        // Check if have space
        $subscriptions = EventSubscription::select()
            ->where('club_code', getClubCode())
            ->where('event_id', $event->id)
            ->where('status', EventSubscription::ACTIVE_STATUS)
            ->count();

        $vehicles = EventSubscription::select()
            ->where('club_code', getClubCode())
            ->where('event_id', $event->id)
            ->where('status', EventSubscription::ACTIVE_STATUS)
            ->where('vehicle', true)
            ->count();

        $companions = EventSubscription::select()
            ->where('club_code', getClubCode())
            ->where('event_id', $event->id)
            ->where('status', EventSubscription::ACTIVE_STATUS)
            ->sum('companions');

        // Check participants
        if($subscriptions+1 > $max_participants) {
            return response()->json([ 'status' => 'error', 'message' => __('events.error-subscribe-event.max-participants') ]);
        }

        // Check companions
        if($companions + (int) $request->get('companions') > $max_companions) {
            return response()->json([ 'status' => 'error', 'message' => __('events.error-subscribe-event.max-companions') ]);
        }

        // Check vehicles
        if ($subscript_vehicle) {
            if($vehicles+1 > $max_vehicles) {
                return response()->json([ 'status' => 'error', 'message' => __('events.error-subscribe-event.max-vehicles') ]);
            }
        }


        $user =  User::getAuthenticatedUser();
        $member_class = $user->member_class;
        $bank_account = $user->bank_account;

        $member_params = false;

        foreach($event->class_data as $class) {
            if ($class->member_class_id == $member_class->id) {
                $member_params = $class;
                
                break;
            }
        }

        $date_actual = strtotime(date('Y-m-d H:i:s'));
        $date_subscript = strtotime($member_params->start_subscription_date . ' 00:00:00');

        if ($date_actual < $date_subscript) {
            return response()->json([ 'status' => 'error', 'message' => __('events.error-subscribe-event.start_subscription_date', [ 'member_class_name' => $member_class->name, 'date' => date('d/m/Y', $date_subscript) ]) ]);
        }

        $price = 0;
        
        // Participant price
        $price += (float) $member_params->participant_value;

        // Vehicle price
        if ($request->vehicle === true || $request->vehicle === 1 || $request->vehicle === '1') {
            $price += (float) $member_params->vehicle_value;
        }

        // Companions price
        if ((int) $request->companions > 0) {
            $price += (float) $member_params->companion_value * (int) $request->companions;
        } 

        $price = floatval($price);
        $account_balance = floatval($bank_account->balance);

        if ($account_balance < $price) {
            return response()->json([ 'status' => 'error', 'message' => __('events.error-subscribe-event.without_balance', [ 'name' => $event->name, 'value' => 'R$' . number_format($price, 2, ',', '.') ]) ]);
        }

        $club_account = ClubBankAccount::Get();

        DB::beginTransaction();

        try {
            // Launch Debit
            $launch = new AccountLaunch();
            $launch->club_code = getClubCode();
            $launch->account_number = $bank_account->account_number;
            $launch->created_by = User::getAuthenticatedUserId();
            $launch->amount = $price;
            $launch->type = AccountLaunch::DEBIT_TYPE;
            $launch->description = AccountLaunch::EVENT_SUBSCRIBE_DESCRIPTION;
            $launch->mode = AccountLaunch::AUTOMATIC_MODE;
            $launch->event_id = $event->id;
            $launch->save();

            // Discount price on user bank account
            $bank_account->balance -= $launch->amount;
            $bank_account->save();

            // Launch credit on club account
            $club_launch = new ClubLaunch();
            $club_launch->club_code = getClubCode();
            $club_launch->created_by = User::getAuthenticatedUserId();
            $club_launch->amount = $launch->amount;
            $club_launch->type = ClubLaunch::CREDIT_TYPE;
            $club_launch->description = ClubLaunch::EVENT_SUBSCRIBE_USER_DESCRIPTION;
            $club_launch->mode = ClubLaunch::AUTOMATIC_MODE;
            $club_launch->user_description = '-';
            $club_launch->save();

            // Add amount on club account
            $club_account->balance += $launch->amount;
            $club_account->save();

            // Create event subscription
            $event_subscription = new EventSubscription();
            $event_subscription->club_code = getClubCode();
            $event_subscription->event_id = $event->id;
            $event_subscription->user_id = User::getAuthenticatedUserId();
            $event_subscription->status = EventSubscription::ACTIVE_STATUS;
            $event_subscription->vehicle = $subscript_vehicle;
            $event_subscription->companions = (int) $request->get('companions');
            $event_subscription->amount = $launch->amount;
            
            $event_subscription->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        
            return response()->json([ 'status' => 'error', 'message' => __('events.error-subscribe-event.generic', [ 'name' => $event->name, 'error' => $e->getMessage() ]) ]);
        }
        
        return response()->json([ 'status' => 'success', 'data' => (new EventResource($event)), 'message' => __('events.success-subscribe-event', [ 'name' => $event->name ]) ]);
    }

    /**
     * Unsubscribe on event
     * 
     * @author Davi Souto
     * @since 24/08/2021
     */
    public function Unsubscribe(Event $event, UnsubscribeEventRequest $request)
    {
        $this->validateClub($event->club_code, 'event');

        $subscription = EventSubscription::select()
            ->where('club_code', getClubCode())
            ->where('user_id', User::getAuthenticatedUserId())
            ->where('event_id', $event->id)
            ->where('status', EventSubscription::ACTIVE_STATUS)
            ->first();
    
        // Check if you are already registered for the event
        if (! $subscription){
            return response()->json([ 'status' => 'error', 'message' => __('events.error-unsubscribe-event.not-found', [ 'name' => $event->name ]) ]);
        }

        $date_actual = strtotime(date('Y-m-d H:i:s'));
        $date_limit = strtotime($event->unsubscribe_date_limit . ' 00:00:00');

        if ($date_actual < $date_limit) {
            return response()->json([ 'status' => 'error', 'message' => __('events.error-unsubscribe-event.unsubscribe_date_limit', [ 'date' => date('d/m/Y', $date_subscript) ]) ]);
        }
        
        $club_account = ClubBankAccount::Get();

        DB::beginTransaction();

        try {
            $value = (float) $subscription->amount;
            $value -= (float) $event->retention_value;

            if ($value < 0) {
                $value = 0;
            }

            $subscription->status = EventSubscription::INACTIVE_STATUS;
            $subscription->reason = $request->get('reason');
            $subscription->save();
    
            $bank_account = $subscription->user->bank_account;
    
            // Launch Credit
            $launch = new AccountLaunch();
            $launch->club_code = getClubCode();
            $launch->account_number = $bank_account->account_number;
            $launch->created_by = User::getAuthenticatedUserId();
            $launch->amount = $value;
            $launch->type = AccountLaunch::CREDIT_TYPE;
            $launch->description = AccountLaunch::EVENT_UNSUBSCRIBE_DESCRIPTION;
            $launch->mode = AccountLaunch::AUTOMATIC_MODE;
            $launch->event_id = $event->id;
            $launch->save();
    
            // Discount price on user bank account
            $bank_account->balance += $launch->amount;
            $bank_account->save();
    
            // Launch debit on club account
            $club_launch = new ClubLaunch();
            $club_launch->club_code = getClubCode();
            $club_launch->created_by = User::getAuthenticatedUserId();
            $club_launch->amount = $launch->amount;
            $club_launch->type = ClubLaunch::DEBIT_TYPE;
            $club_launch->description = ClubLaunch::EVENT_UNSUBSCRIBE_USER_DESCRIPTION;
            $club_launch->mode = ClubLaunch::AUTOMATIC_MODE;
            $club_launch->user_description = '-';
            $club_launch->save();
    
            // Add amount on club account
            $club_account->balance -= $launch->amount;
            $club_account->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([ 'status' => 'error', 'message' => __('events.error-unsubscribe-event.generic') ]);
        }


        return response()->json([ 'status' => 'success', 'data' => $subscription ]);
    }

    /**
     * Cancel event
     * 
     * @author Davi Souto
     * @since 02/07/2021
     */
    public function Cancel(Event $event, Request $request)
    {
        $this->validateClub($event->club_code, 'event');

        if ($event->status != Event::ACTIVE_STATUS) {
            return response()->json([ 'status' => 'error', 'message' => __('events.error-cancel-event.status', [ 'name' => $event->name ]) ]);
        }

        try {
            DB::beginTransaction();

            $event->status = Event::CANCELLED_STATUS;
            $event->save();

            $subscriptions = EventSubscription::select()
                ->where('club_code', getClubCode())
                ->where('event_id', $event->id)
                ->get();

            $club_account = ClubBankAccount::Get();
            $amount = 0;

            foreach($subscriptions as $subscription) {
                // $subscription->status = EventSubscription::INACTIVE_STATUS;
                // $subscription->save();

                $bank_account = $subscription->user->bank_account;

                // Launch Credit
                $launch = new AccountLaunch();
                $launch->club_code = getClubCode();
                $launch->account_number = $bank_account->account_number;
                $launch->created_by = User::getAuthenticatedUserId();
                $launch->amount = $subscription->amount;
                $launch->type = AccountLaunch::CREDIT_TYPE;
                $launch->description = AccountLaunch::EVENT_CANCEL_DESCRIPTION;
                $launch->mode = AccountLaunch::AUTOMATIC_MODE;
                $launch->event_id = $event->id;
                $launch->save();

                // Add balance on user bank account
                $bank_account->balance += $launch->amount;
                $bank_account->save();

                $amount += $launch->amount;
            }

            // Launch debit on club account
            $club_launch = new ClubLaunch();
            $club_launch->club_code = getClubCode();
            $club_launch->created_by = User::getAuthenticatedUserId();
            $club_launch->amount = $amount;
            $club_launch->type = ClubLaunch::DEBIT_TYPE;
            $club_launch->description = ClubLaunch::EVENT_CANCEL_USER_DESCRIPTION;
            $club_launch->mode = ClubLaunch::AUTOMATIC_MODE;
            $club_launch->user_description = '-';
            $club_launch->save();

            // Remove amount on club account
            $club_account->balance -= $amount;
            $club_account->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([ 'status' => 'error', 'message' => __('events.error-cancel-event.generic', [ 'name' => $event->name, 'error' => $e->getMessage() ]) ]);
        }

        return response()->json([ 'status' => 'success', 'data' => (new EventResource($event)), 'message' => __('events.success-cancel-event', [ 'name' => $event->name ]) ]);
    }

    public function Members(Event $event, Request $request)
    {
        $this->validateClub($event->club_code, 'event');

        $members = EventSubscription::select()
            ->where('club_code', getClubCode())
            ->where('event_id', $event->id)
            ->where('status', EventSubscription::ACTIVE_STATUS)
            ->get();

        return response()->json([ 'status' => 'success', 'data' => EventMemberResource::collection($members) ]);
    }
}
