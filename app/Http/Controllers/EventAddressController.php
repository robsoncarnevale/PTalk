<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Event;
use App\Models\EventAddress;

use App\Http\Resources\EventAddress as EventAddressResource;

use DB;
use Exception;
use Auth;

/**
 * Event Address Controller
 *
 * @author Davi Souto
 * @since 29/05/2021
 */
class EventAddressController extends Controller
{
    protected $only_admin = false;

    public function Get(Request $request, User $user)
    {
        $address = EventAddress::select()
            ->where('user_id', $user->id)
            ->where('club_code', getClubCode())
            ->first();

        return response()->json([ 'status' => 'success', 'data' => new EventAddressResource($address) ]);
    }

    public function Create(Request $request, Event $event)
    {
        $address = new EventAddress();
        
        $address->fill($request->all());
        $address->club_code = getClubCode();
        $address->event_id = $event->id;
        
        $address->save();
        $address->findLatLon();

        // Remove others address
        $check_others = EventAddress::select()
            ->where('club_code', getClubCode())
            ->where('event_id', $event->id)
            ->where('id', '<>', $address->id)
            ->delete();

        return response()->json([ 'status' => 'success', 'data' => new EventAddressResource($address) ]);
    }

    public function Update(Request $request, Event $event, EventAddress $address)
    {
        $this->validateClub($address->club_code, 'address');

        $address = $address->fill($request->all());
        $address->save();
        $address->findLatLon();

        // Remove others address
        $check_others = EventAddress::select()
            ->where('club_code', getClubCode())
            ->where('event_id', $event->id)
            ->where('id', '<>', $address->id)
            ->delete();

        return response()->json([ 'status' => 'success', 'data' => new EventAddressResource($address) ]);
    }

    public function Delete(Request $request, Event $event, EventAddress $address)
    {
        $this->validateClub($address->club_code, 'address');

        $address->delete();

        return response()->json([ 'status' => 'success', 'data' => new EventAddressResource($address) ]);
    }
}
