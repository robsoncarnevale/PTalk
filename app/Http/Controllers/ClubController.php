<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
            'vehicles'  =>  100,
            'members'   =>  65,
            'next_events'   =>  3,
        ];

        return response()->json([ 'status' => 'success', 'data' => $status ]);
    }
}
