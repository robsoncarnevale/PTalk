<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Requests\EventRequest;

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
        return (new \App\Http\Controllers\EventsController())->List($request);
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
}
