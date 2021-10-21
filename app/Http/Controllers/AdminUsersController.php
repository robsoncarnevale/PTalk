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

use App\Http\Resources\AdministratorsResource;

use DB;
use Exception;

class AdminUsersController extends Controller
{
    protected $only_admin = true;

    public function List(Request $request)
    {
        return UsersController::List($request, 'admin');
    }

    public function Create(UserRequest $request)
    {
        return UsersController::Create($request, 'admin');
    }

    public function Update(UserRequest $request, $user_id)
    {
        return UsersController::Update($request, $user_id, 'admin');
    }

    public function Delete(Request $request, $user_id)
    {
        return UsersController::Delete($request, $user_id, 'admin');
    }

    public function Get(Request $request, $user_id)
    {
        $user = User::where('id', $user_id)
                    ->where('type', 'admin')
                    ->first();

        if(!$user)
            return response()->json(['status' => 'error', 'message' => __('auth.user-not-found')], 404);

        return new AdministratorsResource($user);
    }
}
