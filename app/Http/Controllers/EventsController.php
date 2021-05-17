<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use App\Http\Requests\EventRequest;

use App\Models\Event;
use App\Models\User;
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
        $events = Event::select()
            ->where('club_code', getClubCode())
            ->where('deleted', false)
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
            $event->saveHistory(false);

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
            $event->saveHistory($old_data);


            DB::commit();
        } catch(Exception $e) {
            DB::rollback();

            return response()->json([ 'status' => 'error', 'message' => __('events.error-update', [ 'error' => $e->getMessage() ]) ]);
        }

        return response()->json([ 'status' => 'success', 'data' => (new EventResource($event)), 'message' => __('events.success-update') ]);
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
}
