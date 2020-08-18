<?php

namespace App\Http\Controllers\Mobile;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

use App\Models\User;
use App\Http\Resources\User as UserResource;
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
            ->where('active', true)
            ->where('deleted', false)
            ->where('phone', $phone)
            ->where('approval_status', User::APPROVED_STATUS_APPROVAL)
            ->where('club_code', $request->get('club_code'))
            ->first();

        if (! $user)
            return response()->json(['message' => __('auth.user-not-found'), 'status' => 'error', 'code' => 'user.not-found' ], 404);

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
            ->where('active', true)
            ->where('deleted', false)
            ->where('phone', $phone)
            ->where('club_code', $request->get('club_code'))
            ->first();

        if (! $user)
            return response()->json(['message' => __('auth.user-not-found'), 'status' => 'error', 'code' => 'user.not-found' ], 404);

        if (! $user->testAccessCode($code) && ! $debug)
            return response()->json(['message' => __('auth.code-invalid'), 'status' => 'error', 'code' => 'code.invalid' ], 401);

        $session = $user->only(['id', 'name', 'email', 'type', 'photo', 'photo_url', 'privilege_id', 'company']);

        $explode_name = explode(" ", $session['name']);

        $session['first_name'] = $explode_name[0];
        $session['last_name'] =  (count($explode_name) > 1) ? end($explode_name) : '';
        $session['vehicles_count'] = \App\Models\Vehicle::where('user_id', $session['id'])->count();
        $session['events_count'] = 0;
        $session['club_code'] = $request->get('club_code');

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
                'session' => (new UserResource($session)),
                'access_token' => $token,
                'token_type' => 'bearer',
                // 'expires_in' => $expires_in,
            ],
            'status' => 'success',
            'code' => 200,
        ]);
    }

}
