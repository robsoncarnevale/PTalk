<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use App\Http\Requests\EventRequest;

use App\Models\Event;
use App\Models\User;
use App\Http\Resources\Event as EventResource;
use App\Http\Resources\EventCollection;

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

        return response()->json([ 'status' => 'success', 'data' => (new EventResource($event)) ]);
    }

    /**
     * Create event
     * @author Davi Souto
     * @since 24/08/2020
     */
    public function Create(EventRequest $request)
    {
        $event = new Event();

        $event->fill($request->all());
        $event->club_code = getClubCode();
        $event->created_by = User::getAuthenticatedUserId();
        $event->status = Event::ACTIVE_STATUS;
        $event->save();

        return response()->json([ 'status' => 'success', 'data' => (new EventResource($event)) ]);
    }

    /**
     * Update event
     * @author Davi Souto
     * @since 24/08/2020
     */
    public function Update(Event $event, EventRequest $request)
    {
        $event->fill($request->all());
        $event->save();

        return response()->json([ 'status' => 'success', 'data' => (new EventResource($event)) ]);
    }

    /**
     * Delete event
     * @author Davi Souto
     * @since 24/08/2020
     */
    public function Delete(Event $event, EventRequest $request)
    {
        $this->validateClub($event->club_code, 'event');

        $event->deleted = true;
        $event->save();

        return response()->json([ 'status' => 'success', 'data' => (new EventResource($event)) ]);
    }
}
