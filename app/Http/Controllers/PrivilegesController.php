<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\PrivilegeGroup;

/**
 * Privileges Controller
 *
 * @author Davi Souto
 * @since 06/05/2020
 */
class PrivilegesController extends Controller
{
    /**
     * List Privileges Groups
     *
     * @author Davi Souto
     * @since 08/06/2020
     */
    function ListGroups(Request $request)
    {
        $privileges = PrivilegeGroup::select('id', 'name', 'created_at', 'updated_at')
            ->where('club_code', getClubCode())
            ->get();

        return response()->json([ 'status' => 'success', 'data' => $privileges ]);
    }

    /**
     * Get Privilege Group
     *
     * @author Davi Souto
     * @since 08/06/2020
     */
    function GetGroup(Request $request, $privilege_group_id)
    {
        $privileges = PrivilegeGroup::select('id', 'name', 'created_at', 'updated_at')
            ->where('club_code', getClubCode())
            ->where('id', $privilege_group_id)
            ->first();
        
        if (! $user)
            return response()->json([ 'status' => 'error', 'message' => __('privileges.groups.not-found') ]);

        return response()->json([ 'status' => 'success', 'data' => $privilege ]);
    }

}
