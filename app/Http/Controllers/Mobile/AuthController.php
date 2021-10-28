<?php

namespace App\Http\Controllers\Mobile;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

use App\Models\User;
use App\Http\Resources\SessionMobile as SessionMobileResource;
// use App\Models\AuthTokens;
// use App\Models\HasPrivilege;

/**
 * Authentication Mobile Controller
 *
 * @author Davi Souto
 * @since 01/08/2020
 */
class AuthController extends Controller
{
    /**
     * Request code to access mobile application
     * 
     * @author Davi Souto
     * @since 01/08/2020
     */
    public function RequestAccessCode(Request $request){
        $phone = preg_replace('#[^0-9]#is', '', $request->get('phone'));

        if (empty($phone))
            return response()->json(['message' => __('auth.phone-invalid'), 'status' => 'error', 'code' => 'phone.invalid' ], 404);

        $user = User::select()
            ->with('club:code,name,primary_color,contact_mail')
            ->where('status', '<>', User::INACTIVE_STATUS)
            ->where('deleted', false)
            ->where('phone', $phone)
            ->where('approval_status', User::APPROVED_STATUS_APPROVAL)
            ->where('club_code', $request->get('club_code'))
            ->first();

        if (! $user)
            return response()->json(['message' => __('auth.user-not-found'), 'status' => 'error', 'code' => 'user.not-found' ], 404);

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


        $user = $user->generateNewAccessCode();
        $user->save();

        $sms = new \App\Http\Services\SmsService('aws_sns');
        $sms->send(55, $phone, 'Seu código de acesso ao ' . $user->club->name . ': ' . $user->getAccessCode());

        return response()->json(['message' => __('auth.code-sent'), 'status' => 'success'], 200);
    }

    /**
     * Generate bearer token receiving phone and access code
     * 
     * @author Davi Souto
     * @since 01/08/2020
     */
    public function AccessWithCode(Request $request, $code){
        $phone = preg_replace('#[^0-9]#is', '', $request->get('phone'));
        $debug = (in_array(env('APP_ENV'), ['local', 'homolog', 'staging']) && $request->get('debug'));

        if (empty($phone))
            return response()->json(['message' => __('auth.phone-invalid'), 'status' => 'error', 'code' => 'phone.invalid' ], 404);

        $user = User::select()
            ->where('status', '<>', User::INACTIVE_STATUS)
            ->where('approval_status', User::APPROVED_STATUS_APPROVAL)
            ->where('deleted', false)
            ->where('phone', $phone)
            ->where('club_code', $request->get('club_code'))
            ->where('type', 'member')
            ->first();

        if (! $user)
            return response()->json(['message' => __('auth.user-not-found'), 'status' => 'error', 'code' => 'user.not-found' ], 404);

        if (! $user->testAccessCode($code) && ! $debug)
            return response()->json(['message' => __('auth.code-invalid'), 'status' => 'error', 'code' => 'code.invalid' ], 401);

        $session = new SessionMobileResource($user);
        // dd($session->toArray);
        $header = base64url_encode(json_encode([ 'alg' => 'HS256', 'typ' => 'JWT' ]));
        $payload = base64url_encode(json_encode($session));
        $sign = base64url_encode(hash_hmac('sha256', $header . $payload, env('JWT_SECRET'), true));

        $token = $header . '.' . $payload . '.' . $sign;

        // Delete last access code
        $user->access_code = null;
        $user->save();

        unset($session['club_code']);

        // $session['privileges'] = HasPrivilege::select('privilege_action')
        //     ->where('privilege_group_id', $session['privilege_id'])
        //     ->get()
        //     ->pluck('privilege_action');

        return response()->json([
            'data' => [
                'session' => $session,
                'access_token' => $token,
                'token_type' => 'bearer',
                // 'expires_in' => $expires_in,
            ],
            'status' => 'success',
            'code' => 200,
        ]);
    }

}
