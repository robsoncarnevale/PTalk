<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

use App\Models\User;
use App\Http\Resources\User as UserResource;

/**
 * Password Controller
 *
 * @author Davi Souto
 * @since 22/05/2020
 */
class PasswordController extends Controller
{
    protected $ignore_privileges = true;
    protected $only_admin = false;

    /**
     * Generate new password
     */
    public function FirstAccess(Request $request, $club_code, $token)
    {
        $user = User::select()
            ->where('club_code', $club_code)
            ->where('new_password_token', $token)
            ->where('deleted', false)
            ->where('status', '<>', User::INACTIVE_STATUS)
            ->where('approval_status', User::APPROVED_STATUS_APPROVAL)
            ->first();

        if (! $user) {
            return response()->json([ 'status' => 'error', 'message' => __('password.token-expired'), 'code' => 'token-expired' ]);
        }

        if (strtotime(date('Y-m-d H:i:s')) > strtotime($user->new_password_token_duration)) {
            return response()->json([ 'status' => 'error', 'message' => __('password.token-expired'), 'code' => 'token-expired' ]);
        }

        if ($request->isMethod('post')) {
            $phone = preg_replace('#[^0-9]#is', '', $request->get('phone'));

            if ($user->phone != $phone) {
                return response()->json([ 'status' => 'error', 'message' => __('password.phone-invalid'), 'code' => 'phone-invalid' ]);
            }

            $validator = self::validate($request, [
                'password'  =>  'required|min:6|max:15|confirmed',
            ]);
            
            if ($validator) {
                return $validator;
            }

            $user->new_password_token = null;
            $user->new_password_token_duration = null;
            $user->email_verified_at = date('Y-m-d H:i:s');

            $user->generatePassword($request->get('password'));
            $user->save();
        }


        return response()->json([ 'status' => 'success', 'message' => __('password.success-first-access'), 'data' => (new UserResource($user)) ], 200);
    }

    public function Forget(Request $request, $club_code, $token)
    {
        $user = User::select()
            ->where('club_code', $club_code)
            ->where('forget_password_token', $token)
            ->where('deleted', false)
            ->where('status', '<>', User::INACTIVE_STATUS)
            ->where('approval_status', User::APPROVED_STATUS_APPROVAL)
            ->first();

        if (! $user) {
            return response()->json([ 'status' => 'error', 'message' => __('password.token-expired'), 'code' => 'token-expired' ]);
        }

        if (strtotime(date('Y-m-d H:i:s')) > strtotime($user->forget_password_token_duration)) {
            return response()->json([ 'status' => 'error', 'message' => __('password.token-expired'), 'code' => 'token-expired' ]);
        }

        if ($request->isMethod('post')) {
            $phone = preg_replace('#[^0-9]#is', '', $request->get('phone'));

            if ($user->phone != $phone) {
                return response()->json([ 'status' => 'error', 'message' => __('password.phone-invalid'), 'code' => 'phone-invalid' ]);
            }

            $validator = self::validate($request, [
                'password'  =>  'required|min:6|max:15|confirmed',
            ]);
            
            if ($validator) {
                return $validator;
            }

            $user->forget_password_token = null;
            $user->forget_password_token_duration = null;

            $user->generatePassword($request->get('password'));
            $user->save();
        }

        return response()->json([ 'status' => 'success', 'message' => __('password.success-forget'), 'data' => (new UserResource($user)) ], 200);
    }

    public function GetFirstAccesToken(Request $request, $club_code, $email)
    {
        $user = User::select()
            ->where('club_code', $club_code)
            ->where('email', $email)
            ->where('deleted', false)
            ->where('status', '<>', User::INACTIVE_STATUS)
            ->where('approval_status', User::APPROVED_STATUS_APPROVAL)
            ->first();

        if (! $user) {
            return response()->json([ 'status' => 'error', 'message' => __('password.user-not-found'), 'code' => 'user-not-found' ]);
        }

        // Verify if user has verified email
        if ($user->email_verified_at) {
            return response()->json([ 'status' => 'error', 'message' => __('password.already-confirmed'), 'code' => 'already-confirmed' ]);
        }

        $user->new_password_token = md5(uniqid(rand(), true));
        $user->new_password_token_duration = date('Y-m-d H:i:s', strtotime("+1 day"));
        $user->save();

        try
        {
            Mail::to($user->email)
                ->send(new \App\Mail\RegisterMail($user));
        } catch(\Exception $e) {

        }

        return response()->json([ 'status' => 'success', 'message' => __('password.success-get-first-access-token'), 'data' => (new UserResource($user)) ], 200);
    }

    public function GetForgetToken(Request $request, $club_code, $email)
    {
        $user = User::select()
            ->where('club_code', $club_code)
            ->where('email', $email)
            ->where('deleted', false)
            ->where('status', '<>', User::INACTIVE_STATUS)
            ->where('approval_status', User::APPROVED_STATUS_APPROVAL)
            ->first();

        if (! $user) {
            return response()->json([ 'status' => 'error', 'message' => __('password.user-not-found'), 'code' => 'user-not-found' ]);
        }

        $user->forget_password_token = md5(uniqid(rand(), true));
        $user->forget_password_token_duration = date('Y-m-d H:i:s', strtotime("+1 hour"));
        $user->save();

        try
        {
            Mail::to($user->email)
                ->send(new \App\Mail\ForgetPasswordMail($user));
        } catch(\Exception $e) {

        }

        return response()->json([ 'status' => 'success', 'message' => __('password.success-get-forget-token'), 'data' => (new UserResource($user)) ], 200);
    }
}
