<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;
use App\Models\AuthTokens;

/**
 * Authentication Controller
 *
 * @author Davi Souto
 * @since 22/05/2020
 */
class AuthController extends Controller
{
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
            $session = $this->guard()->user()->only(['name', 'email']);
            $session['first_name'] = explode(" ", $session['name'])[0];

            return response()->json([
                'data' => [
                    'session' => $session,
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
