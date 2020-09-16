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
    protected $only_admin = false;

    /**
     * Returns club status for dashboard
     *
     * @author Davi Souto
     * @since 03/06/2020
     */
    public function GetStatus(Request $request)
    {
        $members_count = User::where('club_code', getClubCode())
            ->where('deleted', false)
            ->where('status', '<>', User::INACTIVE_STATUS)
            ->where('status', '<>', User::BANNED_STATUS)
            ->where('approval_status', User::APPROVED_STATUS_APPROVAL)
            ->count();

        $vehicles_count = Vehicle::where('club_code', getClubCode())
            ->whereHas('user', function($q){
                $q->where('deleted', false)
                  ->where('status', '<>', User::INACTIVE_STATUS)
                  ->where('status', '<>', User::BANNED_STATUS)
                  ->where('approval_status', User::APPROVED_STATUS_APPROVAL)
                  ->where('club_code', getClubCode());
            })
            ->where('deleted', false)
            ->count();

        $status = [
            'vehicles'  =>  $vehicles_count,
            'members'   =>  $members_count,
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
