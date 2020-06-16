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
    function ListGroups(Request $request, $type = false)
    {
        $privileges = PrivilegeGroup::select('id', 'name', 'created_at', 'updated_at')
            ->where('club_code', getClubCode());

        if ($type)
            $privileges = $privileges->where('type', $type);

        $privileges = $privileges->get();

        return response()->json([ 'status' => 'success', 'data' => $privileges ]);
    }

    /**
     * List admin privileges groups
     *
     * @author Davi Souto
     * @since 15/06/2020
     */
    function ListGroupsAdmins(Request $request)
    {
        return $this->ListGroups($request, 'admin');
    }

    /**
     * List members privileges groups
     *
     * @author Davi Souto
     * @since 15/06/2020
     */
    function ListGroupsMembers(Request $request)
    {
        return $this->ListGroups($request, 'member');
    }

    /**
     * Get Privilege Group
     *
     * @author Davi Souto
     * @since 08/06/2020
     */
    function GetGroup(Request $request, $privilege_group_id)
    {
        $privilege = PrivilegeGroup::select('id', 'name', 'created_at', 'updated_at')
            ->where('club_code', getClubCode())
            ->where('id', $privilege_group_id)
            ->first();
        
        if (! $user)
            return response()->json([ 'status' => 'error', 'message' => __('privileges.groups.not-found') ]);

        return response()->json([ 'status' => 'success', 'data' => $privilege ]);
    }

}
