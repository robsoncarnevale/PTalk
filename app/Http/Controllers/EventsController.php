<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use App\Http\Requests\EventRequest;

use App\Models\Event;
use App\Models\User;
use App\Models\MemberClass;
use App\Http\Resources\Event as EventResource;
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
        $events = false;

        if (User::isMobile()) {
            $events = Event::select()
                ->where('club_code', getClubCode())
                ->where('deleted', false)
                ->where('status', Event::ACTIVE_STATUS)
                ->jsonPaginate(20);
        } else {
            $events = Event::select()
                ->where('club_code', getClubCode())
                ->where('deleted', false)
                ->jsonPaginate(20);
        }

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

        // $event->with('history');

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
    
            $event->fill($request->all());
            $event->club_code = getClubCode();
            $event->name = ucwords($event->name);
            $event->created_by = User::getAuthenticatedUserId();
            $event->status = Event::DRAFT_STATUS;

            // Photo upload
            if ($request->has('cover_picture')) {
                $event->upload($request->file('cover_picture'));
            }

            $event->save();
            $event->saveHistory(false, $request);
            $event->saveClassData($request->get('class'));

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

            if ($request->get('class')) {
                $old_data['class'] = EventResource::mapClassData($event->class_data->toArray());
            
                foreach($old_data['class'] as $i_old_data_class => $v_old_data_class) {
                    $old_data['class'][$i_old_data_class] = (array) $v_old_data_class;
                }
            }


            $event->fill($request->all());
            $event->name = ucwords($event->name);

            // Photo remove and upload
            if ($request->has('remove_cover_picture') && $request->get('remove_cover_picture') == 'true')
            {
                if (! empty($event->cover_picture) && Storage::disk('images')->exists($event->cover_picture)) {
                    Storage::disk('images')->delete($event->cover_picture);
                }

                $event->cover_picture = null;
            } else if ($request->has('cover_picture'))
            {
                $event->upload($request->file('cover_picture'));
            }

            $event->save();
            $event->saveHistory($old_data, $request);
            $event->saveClassData($request->get('class'));


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
     * Start event
     * 
     * @param \App\Models\Event $event
     * @author Davi Souto
     * @since 20/05/2021
     */
    public function Cancel(Event $event)
    {
        $this->validateClub($event->club_code, 'event');

        return response()->json([ 'status' => 'success', 'data' => (new EventResource($event)), 'message' => __('events.success-cancel-event', [ 'name' => $event->name ]) ]);
    }
}
