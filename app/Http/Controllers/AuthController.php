<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

use App\Models\User;
use App\Models\AuthTokens;
use App\Models\HasPrivilege;
use App\Http\Resources\User as UserResource;

use App\Http\Resources\Session as SessionResource;

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
            $user = $this->guard()->user();

            // User inactive
            if ($user->status == User::INACTIVE_STATUS)
                return response()->json(['message' => __('auth.incorrect'), 'status' => 'error' ]);

            // User waiting approvation
            if ($user->approval_status == User::WAITING_STATUS_APPROVAL)
                return response()->json(['message' => __('auth.waiting-approval'), 'status' => 'error', 'code' => 'auth.waiting-approval' ]);

            // Uses refused approvation
            if ($user->approval_status == User::REFUSED_STATUS_APPROVAL)
                return response()->json(['message' => __('auth.refused-approval'), 'status' => 'error', 'code' => 'auth.refused-approval' ]);

            // Verify status
            switch($user->status)
            {
                case User::SUSPENDED_STATUS:
                    if (strtotime(date('Y-m-d')) < strtotime($user->suspended_time))
                    {
                        return response()->json([ 'status' => 'error', 'message' => __('auth.suspended', [ 'until' => date('d/m/Y', strtotime($user->suspended_time)), 'reason' => $user->status_reason ]), 'code' => 'auth.suspended' ]);
                    } else
                    {
                        $user->status = User::ACTIVE_STATUS;
                        $user->suspended_time = null;
                        $user->status_reason = null;

                        $user->save();
                        $user->saveStatusHistory();
                    }
                break;
                
                case User::BLOCKED_STATUS:
                    return response()->json([ 'status' => 'error', 'message' => __('auth.blocked', [ 'reason' => $user->status_reason ]), 'code' => 'auth.blocked' ]);

                case User::BANNED_STATUS:
                    return response()->json([ 'status' => 'error', 'message' => __('auth.banned', [ 'reason' => $user->status_reason ]), 'code' => 'auth.banned' ]);
            }

            return response()->json([
                'data' => [
                    'session' => (new SessionResource($user)),
                    'access_token' => $token,
                    'token_type' => 'bearer',
                    'expires_in' => $this->guard()->factory()->getTTL() * 60
                ],
                'status' => 'success',
                'code' => 200,
            ]);
        }

        return response()->json(['message' => __('auth.incorrect'), 'status' => 'error', 'code' => 'auth.incorrect' ]);
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
