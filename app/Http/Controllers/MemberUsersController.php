<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

use App\Models\User;
use App\Models\Vehicle;

use DB;
use Exception;

/**
 * Member Users Controller
 *
 * @author Davi Souto
 * @since 15/06/2020
 */
class MemberUsersController extends Controller
{
    protected $only_admin = false;

    /**
     * List Members
     *
     * @author Davi Souto
     */
    public function List(Request $request)
    {
        return UsersController::List($request, 'member');
    }

    /**
     * Create Members
     *
     * @author Davi Souto
     * @since 15/06/2020
     */
    public function Create(Request $request)
    {
        return UsersController::Create($request, 'member');
    }

    /**
     * Update Members
     *
     * @author Davi Souto
     * @since 15/06/2020
     */
    public function Update(Request $request, $user_id)
    {
        return UsersController::Update($request, $user_id, 'member');
    }

    /**
     * Delete Members
     *
     * @author Davi Souto
     */
    public function Delete(Request $request, $user_id)
    {
        return UsersController::Delete($request, $user_id, 'member');
    }

    /**
     * Get Members
     * 
     * @author Davi Souto
     * @since 15/06/2020
     */
    public function Get(Request $request, $user_id)
    {
        return UsersController::Get($request, $user_id, 'member');
    }

    ////////////////////////

    /**
     * Returns members waiting approval
     * 
     * @author Davi Souto
     * @since 18/06/2020
     */
    public function WaitingApproval(Request $request)
    {
        $users = User::select('id', 'name', 'email', 'privilege_id', 'document_cpf', 'document_rg', 'cell_phone', 'company', 'created_at', 'updated_at')
            // ->with('privilege_group')
            ->with('privilege_group:id,name')
            ->where('club_code', getClubCode())
            ->where('deleted', false)
            ->where('approval_status', 'waiting')
            ->where('type', 'member')
            ->orderBy('created_at')
            ->jsonPaginate(25, 3);

        return response()->json([ 'status' => 'success', 'data' => $users ]);
    }
}