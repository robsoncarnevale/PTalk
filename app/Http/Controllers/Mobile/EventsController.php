<?php

namespace App\Http\Controllers\Mobile;

use Illuminate\Http\Request;
use App\Http\Requests\EventRequest;
use App\Http\Requests\SubscribeEventRequest;

use App\Models\Event;
use App\Models\User;
use App\Models\MemberClass;
use App\Models\EventSubscription;

use App\Http\Resources\Event as EventResource;
use App\Http\Resources\EventMobile as EventMobileResource;
use App\Http\Resources\EventSubscriptionMobile as EventSubscriptionMobileResource;
use App\Http\Resources\EventCollection;

// use App\Models\Event;

/**
 * Mobile Vehicles Controller
 *
 * @author Davi Souto
 * @since 06/08/2020
 */
class EventsController extends Controller
{
    /**
     * List events
     *
     * @author Davi Souto
     * @since 06/08/2020
     */
    public function List(EventRequest $request)
    {
        $events = Event::select()
            ->where('club_code', getClubCode())
            ->where('deleted', false)
            ->where('status', Event::ACTIVE_STATUS)
            ->get();

        $subscriptions = EventSubscription::select()
            ->where('club_code', getClubCode())
            ->where('user_id', User::getAuthenticatedUserId())
            ->with('event')
            ->get();
        

        $result = [
            'events' => EventMobileResource::collection($events),
            'subscriptions' => EventSubscriptionMobileResource::collection($subscriptions),
        ];

        return response()->json([ 'status' => 'success', 'data' => $result ]);
    }

    /**
     * Get event
     *
     * @author Davi Souto
     * @since 06/08/2020
     */
    public function Get(Event $event, EventRequest $request)
    {
        return (new \App\Http\Controllers\EventsController())->Get($event, $request);
    }

    /**
     * Subscribe in event
     * 
     * @author Davi Souto
     * @since 24/05/2021
     */
    public function Subscribe(Event $event, SubscribeEventRequest $request)
    {
        return (new \App\Http\Controllers\EventsController())->Subscrive($event, $request);
    }
}
