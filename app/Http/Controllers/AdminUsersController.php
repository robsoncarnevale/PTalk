<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

use App\Models\User;
use App\Models\Vehicle;

use DB;
use Exception;

/**
 * Admin Users Controller
 *
 * @author Davi Souto
 * @since 23/05/2020
 */
class AdminUsersController extends Controller
{
    protected $only_admin = true;

    /**
     * List Administrator
     *
     * @author Davi Souto
     */
    public function List(Request $request)
    {
        return UsersController::List($request, 'admin');
    }

    /**
     * Create Administrator
     *
     * @author Davi Souto
     * @since 08/06/2020
     */
    public function Create(UserRequest $request)
    {
        return UsersController::Create($request, 'admin');
    }

    /**
     * Update Administrator
     *
     * @author Davi Souto
     * @since 07/06/2020
     */
    public function Update(UserRequest $request, $user_id)
    {
        return UsersController::Update($request, $user_id, 'admin');
    }

    /**
     * Delete Administrator
     *
     * @author Davi Souto
     */
    public function Delete(Request $request, $user_id)
    {
        return UsersController::Delete($request, $user_id, 'admin');
    }

    /**
     * Get Administrator
     * 
     * @author Davi Souto
     * @since 07/06/2020
     */
    public function Get(Request $request, $user_id)
    {
        return UsersController::Get($request, $user_id, 'admin');
    }
}
