<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Vehicle;
use App\Models\User;

/**
 * Club Controller
 *
 * @author Davi Souto
 * @since 03/06/2020
 */
class ClubController extends Controller
{
    /**
     * Returns club status for dashboard
     *
     * @author Davi Souto
     * @since 03/06/2020
     */
    public function GetStatus(Request $request)
    {
        $status = [
            'vehicles'  =>  Vehicle::count(),
            'members'   =>  User::count(),
            'next_events'   =>  0,
        ];

        if ($status['vehicles'] < 1)
            $status['vehicles'] = '-';

        if ($status['members'] < 1)
            $status['members'] = '-';

        if ($status['next_events'] < 1)
            $status['next_events'] = '-';

        return response()->json([ 'status' => 'success', 'data' => $status ]);
    }
}
