<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\AuthTokens;
use App\Models\HasPrivilege;
use App\Http\Resources\User as UserResource;

/**
 * Authentication Controller
 *
 * @author Davi Souto
 * @since 22/05/2020
 */
class AuthController extends Controller
{
    protected $ignore_privileges = true;
    protected $only_admin = false;

    /**
     * Login in application
     * 
     * @author Davi Souto
     * @since 22/05/2020
     */
    public function Login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if ($token = $this->guard()->attempt($credentials))
        {
            // AuthTokens::createToken($token);
            $session = $this->guard()
                ->user()
                ->only(['id', 'name', 'email', 'type', 'photo_url', 'privilege_id']);

            $session['first_name'] = explode(" ", $session['name'])[0];
            $session['privileges'] = HasPrivilege::select('privilege_action')
                ->where('privilege_group_id', $session['privilege_id'])
                ->get()
                ->pluck('privilege_action');

            return response()->json([
                'data' => [
                    'session' => (new UserResource($session)),
                    'access_token' => $token,
                    'token_type' => 'bearer',
                    'expires_in' => $this->guard()->factory()->getTTL() * 60
                ],
                'status' => 'success',
                'code' => 200,
            ]);
        }

        return response()->json(['message' => 'Email or password incorrect', 'status' => 'error' ]);
    }

    /**
     * Kill session
     *
     * @author Davi Souto
     * @since 23/05/2020
     */
    public function Logout(Request $request)
    {
        dd("Perform logout");
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function guard()
    {
        return Auth::guard();
    }
}
